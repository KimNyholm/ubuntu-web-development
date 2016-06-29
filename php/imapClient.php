<?php

$host=empty($_GET['host']) ?'' : $_GET['host'];
$user=empty($_GET['user']) ?'' : $_GET['user'];
$pw=empty($_GET['pw']) ?'' : $_GET['pw'];

if (empty($host) || empty($user) || empty($pw)){
  echo '<p>To get mail, specify parameters (host, username and password) in url as ' . $_SERVER['REQUEST_URI']. '?=host=hostname&user=username&pw=password</p>';
  echo '<p>Example: ' . $_SERVER['REQUEST_URI']. '?host=mail.mydomain.com&user=name@mydomain.com&pw=1234567</p>';
  } else {
    echo EmailDownload($host, $user, $pw);
  }

function EmailEmbeddedLinkReplace($html, $cid, $link)
{
  // In $html locate src="cid:$cid" and replace with $link.
  $cid='cid:'.substr($cid, 1, strlen($cid)-2);
  $newHtml = str_replace($cid, $link, $html);
  return $newHtml;
}

function EmailConnect($host, $user, $password){
  $hostName = '{'.$host.':143/novalidate-cert}INBOX';
  $inbox = imap_open($hostName,$user,$password) ;
  return $inbox;
}

function trimArray($values){
  $trimmed=array();
  foreach ($values as $value){
    $trimmed[]=trim($value);
  }
  return $trimmed;
}

function extractField($fieldName, $values){
  $index=array_search($fieldName, $values);
  $id=($index === FALSE) ? "" : $values[$index+1];
  return $id ;
}

function extractValue($prefix, $values){
  $result="";
  foreach ($values as $value){
    if (0===strpos($value, $prefix)){
      $result=substr($value, strlen($prefix)+2,-1);
      continue;
    }
  }
  return $result;
}

function extractMimeFileName($values){
  $filename=extractField("X-Attachment-Id:", $values);
  if (empty($filename)){
    $filename=extractValue("filename", $values);
  }
  if (empty($filename)){
    $filename=extractValue("name", $values);
  }
  if (empty($filename)){
    $filename="unknown";
  }
  return $filename ;
}

function fetchImageInfo($mailbox, $emailNumber, $partNo){
  $mime=imap_fetchmime($mailbox, $emailNumber, $partNo, (FT_PEEK));
  $mime= preg_split('/\s+/', $mime);
  $mime=trimArray($mime);
  $id=extractField("Content-ID:", $mime);
  $filename=extractMimeFileName($mime);
  $info=array('id'=>$id, 'filename' => $filename);
  return $info;
}

// Based upon http://php.net/manual/en/function.imap-fetchstructure.php.
function EmailGetPart($mailbox, $emailNumber, $part, $partNo, $result){
  $parameter = array();
  $attachments = array();
  $plainText = '';
  $htmlText = '';
  // GET DATA
  $data = ($partNo) ? imap_fetchbody($mailbox,$emailNumber,$partNo) : imap_body($mailbox, $emailNumber);
  // Any part may be encoded, even plain text messages, so check everything.
  $encoding = $part->encoding ;
  $type=$part->type;
  if ($encoding==ENCQUOTEDPRINTABLE){
      $data = quoted_printable_decode($data);
  } elseif ($encoding==ENCBASE64) {
      $data = base64_decode($data);
  }
  // PARAMETERS
  // get all parameters, like charset, filenames of attachments, etc.
  if($part->ifdparameters) {
    foreach($part->dparameters as $object) {
      $parameter[strtolower($object->attribute)] = $object->value;
    }
  }
  if($part->ifparameters) {
    foreach($part->parameters as $object) {
      $parameter[strtolower($object->attribute)] = $object->value;
    }
  }

  // ATTACHMENT
  // Any part with a filename is an attachment,
  // so an attached text file (type 0) is not mistaken as the message.
  if(isset($parameter['filename']) || isset($parameter['name'])) {
    $filename = ($parameter['filename'])? $parameter['filename'] : $parameter['name'];
    $filename=iconv_mime_decode($filename, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF8');
    $id = isset($part->id) ? $part->id : '' ;
    $attachments[] = array('inline' => false, 'filename' => $filename, 'part' => $partNo, 'data' => $data, 'id' => $id);
  }
  if ($type==TYPEIMAGE){
    $info=fetchImageInfo($mailbox, $emailNumber, $partNo);
    $attachments[] = array('inline' => true, 'filename' => $info['filename'], 'part' => $partNo, 'data' => $data, 'id' => $info['id']);
  }
  if (!empty($data)){
    if ($type==TYPETEXT) {
      // Messages may be split in different parts because of inline attachments,
      // so append parts together with blank row.
      if (strtolower($part->subtype)=='plain') {
          $plainText.= trim($data) ."\n\n";
      } else {
          $htmlText.= $data ."<br><br>";
      }
      // assume all parts are same charset
      $result->charset = $parameter['charset'];
    } elseif ($type==TYPEMESSAGE) {
      // EMBEDDED MESSAGE
      // Many bounce notifications embed the original message as type 2,
      // but AOL uses type 1 (multipart), which is not handled here.
      // There are no PHP functions to parse embedded messages,
      // so this just appends the raw source to the main message.
          $plainText.= $data."\n\n";
    }
  }
  // SUBPART RECURSION
  $result->attachments = array_merge($result->attachments, $attachments);
  $result->plainText   = $result->plainText . $plainText;
  $result->htmlText    = $result->htmlText . $htmlText;
  if (isset($part->parts)){
    $result = EmailGetParts($mailbox,$emailNumber, $part->parts, $partNo, $result);
  }
  return $result;
}

function EmailGetParts($mailbox, $emailNumber, $parts, $partNo, $result){
  if (isset($parts) && count($parts)) {
    foreach ($parts as $partIx=>$subPart){
      $subPartNo = empty($partNo) ? ($partIx+1) : $partNo . '.' . ($partIx+1);
      $result = EmailGetPart($mailbox,$emailNumber, $subPart, $subPartNo, $result);
    }
  }
  return $result ;
}

function DecodeMailHeader($headerInfo, $fieldName){
  $value='';
  if (isset($headerInfo->$fieldName)){
    $value=iconv_mime_decode($headerInfo->$fieldName, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF8');
  }
  return $value ;
}

function EmailGetOne($mailbox, $email_number){
  $structure = imap_fetchstructure($mailbox,$email_number);
  $mail = new stdClass();
  $mail->attachments = array();
  $mail->plainText   = '';
  $mail->htmlText    = '';
  $mail->charset     = 'auto';
  if (empty($tructure->parts)){
    // Simple message.
    $mail = EmailGetPart($mailbox, $email_number, $structure, 0, $mail);
  } else {
    // Multipart message.
    $mail = EmailGetParts($mailbox, $email_number, $structure->parts, 0, $mail);
  }
  $headerInfo= imap_headerinfo($mailbox,$email_number,0);
  $headerInfo->fromaddress = DecodeMailHeader($headerInfo, 'fromaddress') ;
  $headerInfo->toaddress = DecodeMailHeader($headerInfo, 'toaddress') ;
  $headerInfo->ccaddress = DecodeMailHeader($headerInfo, 'ccaddress') ;
  $headerInfo->subject = DecodeMailHeader($headerInfo, 'subject') ;
  $mail->headerInfo=$headerInfo;

  $mail->plainText = iconv($mail->charset, 'UTF8', $mail->plainText);
  $mail->htmlText  = iconv($mail->charset, 'UTF8', $mail->htmlText);
  if (empty($mail->htmlText)){
    $mail->htmlText='<p>'.$mail->plainText.'</p>';
  }
  return $mail;
}

function EmailGetMany($host, $user, $password){
  $mailbox = EmailConnect($host, $user, $password);
  $mails=array();
  if (!empty($mailbox)){
    $emails = imap_search($mailbox, 'ALL');
    if($emails) {
      /* put the newest emails on top */
      rsort($emails);
      foreach($emails as $email_number) {
        $mails[]=EmailGetOne($mailbox, $email_number);
      }
    }
    imap_close($mailbox);
  }
  return $mails;
}

function EmailAttachmentsSave(&$mail){
  $html = '';
  $attachments=$mail->attachments;
  $msgNo=trim($mail->headerInfo->Msgno);
  foreach ($attachments as $attachment) {
    $partNo=$attachment['part'];
    $tmpDir= "imapClient/$msgNo/$partNo";
    $dirExists= is_dir($tmpDir);
    if (!$dirExists){
      $dirExists= mkdir($tmpDir, 0777, true) ;
    }
    $fileName=$attachment['filename'];
    $tmpName = "$tmpDir/$fileName";
    $saved = $dirExists && file_put_contents($tmpName, $attachment['data']);
    $tmpName=htmlentities($tmpName);
    $fileName=htmlentities($fileName);
    if (!$attachment['inline']){
      $html .= '<span><a href="' . $tmpName . '">' . $fileName . '</a> </span>';
    }
    $cid =$attachment['id'];
    if (isset($cid)){
      $mail->htmlText=EmailEmbeddedLinkReplace($mail->htmlText,$cid,$tmpName);
    }
  }
  return $html ;
}

function EmailPrint($mail){
  $headerInfo=$mail->headerInfo;
  $html = '<h4>' . htmlentities($headerInfo->subject) . '</h4>';
  $html .= '<p>From: ' . htmlentities($headerInfo->fromaddress) . '</p>';
  $html .= '<p>To: ' . htmlentities($headerInfo->toaddress) . '</p>';
  $html .= '<div style="background: lightgrey">' . (empty($mail->htmlText) ? ('<p>' . $mail->plainText . '</p>') : $mail->htmlText) . '</div>';
  return $html ;
}

function EmailDownload($host, $user, $password){
  $html = '<head> <meta charset="UTF-8"> </head>';
  $html .= '<h3>Simple imap client</h3>';
  $mails=EmailGetMany($host, $user, $password);
  $count=count($mails);
  $html .= "<p>$user has $count mails at $host.</p>";
  foreach ($mails as $mail){
      $html .= '<hr>';
      $html .= EmailAttachmentsSave($mail);
      $html .= EmailPrint($mail);
  }
  return $html ;
}

?>
