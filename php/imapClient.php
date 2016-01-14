<?php

$host=empty($_GET['host']) ?'' : $_GET['host'];
$user=empty($_GET['user']) ?'' : $_GET['user'];
$pw=empty($_GET['pw']) ?'' : $_GET['pw'];

if (empty($host) || empty($user) || empty($pw)){
  echo '<p>To get mail, specify parameters (host, username and password) in url as ' . $_SERVER['REQUEST_URI']. '?=host=hostname&user=username&pw=password</p>';
  echo '<p>Example: ' . $_SERVER['REQUEST_URI']. '?host=mail.mydomain.com&&user=name@mydomain.com&pw=1234567</p>';
  } else {
    echo EmailDownload($host, 'ldb-cms@kimnyholm.com', 'kny903kny903');
  }

function EmailConnect($host, $user, $password){
  $hostName = '{'.$host.':143/novalidate-cert}INBOX';
  //$hostName = "{$host:143/novalidate-cert}INBOX";
  $inbox = imap_open($hostName,$user,$password) ;
  return $inbox;
}

// Based upon http://php.net/manual/en/function.imap-fetchstructure.php.
function EmailGetPart($inbox, $emailNumber, $part, $partNo, $result){
  $parameter = array();
  $attachments = array();
  $plainText = '';
  $htmlText = '';
  // GET DATA
  $data = imap_fetchbody($inbox,$emailNumber,$partNo);
  // Any part may be encoded, even plain text messages, so check everything.
  $encoding = $part->encoding ;
  if ($encoding==4){
      $data = quoted_printable_decode($data);
  } elseif ($encoding==3) {
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
    $id = isset($part->id) ? $part->id : '';
    $attachments[] = array('filename' => $filename, 'part' => $partNo, 'data' => $data, 'id' => $id);
  }
   // TEXT
  if ($part->type==0 && $data) {
    // Messages may be split in different parts because of inline attachments,
    // so append parts together with blank row.
    if (strtolower($part->subtype)=='plain') {
        $plainText.= trim($data) ."\n\n";
    } else {
        $htmlText.= $data ."<br><br>";
    }
    $charset = $parameter['charset'];  // assume all parts are same charset
  } elseif ($part->type==2 && $data) {
    // EMBEDDED MESSAGE
    // Many bounce notifications embed the original message as type 2,
    // but AOL uses type 1 (multipart), which is not handled here.
    // There are no PHP functions to parse embedded messages,
    // so this just appends the raw source to the main message.
        $plainmsg.= $data."\n\n";
  }
  // SUBPART RECURSION
  $result = array (
    'attachments' => array_merge($result['attachments'], $attachments),
    'plainText'   => $result['plainText'] . $plainText,
    'htmlText'    => $result['htmlText'] . $htmlText,
  );
  if (isset($part->pargs)){
    $result = EmailGetParts($inbox,$emailNumber, $part->parts, $partNo, $result);
  }
  return $result;
}

function EmailGetParts($inbox, $emailNumber, $parts, $partNo, $result){
  foreach ($parts as $partIx=>$subPart){
    $subPartNo = empty($partNo) ? ($partIx+1) : $partNo . '.' . ($partIx+1);
    $result = EmailGetPart($inbox,$emailNumber, $subPart, $subPartNo, $result);
  }
  return $result ;
}

function EmailGetOne($inbox, $email_number){
  $structure = imap_fetchstructure($inbox,$email_number);
  $mail = array (
    'attachments' => array(),
    'plainText'   => '',
    'htmlText'    => '',
  );
  if(isset($structure->parts)){
    $mail = EmailGetParts($inbox, $email_number, $structure->parts, 0, $mail);
  }
  $headerInfo= imap_headerinfo($inbox,$email_number,0);
  $mail['headerInfo']=$headerInfo;
  return $mail;
}

function EmailGetAll($host,$user, $password){
  $inbox = EmailConnect($host, $user, $password);
  $mails=array();
  if (!empty($inbox)){
    $emails = imap_search($inbox, 'ALL');
    if($emails) {
      /* put the newest emails on top */
      rsort($emails);
      foreach($emails as $email_number) {
        $mails[]=EmailGetOne($inbox, $email_number);
      }
    }
    imap_close($inbox);
  }
  return $mails;
}

function EmailAttachmentsSave($mail){
  $html = '';
  $attachments=$mail['attachments'];
  foreach ($attachments as $attachment) {
    $msgNo=trim($mail['headerInfo']->Msgno);
    $partNo=$attachment['part'];
    $tmpDir= "imapClient/$msgNo/$partNo";
    $dirExists= is_dir($tmpDir);
    if (!$dirExists){
      $dirExists= mkdir($tmpDir, 0777, true) ;
    }
    $fileName=$attachment['filename'];
    $tmpName = "$tmpDir/$fileName";
    $saved = $dirExists && file_put_contents($tmpName, $attachment['data']);
    $html .= '<span><a href="' . $tmpName . '">' . $fileName . '</a> </span>';
  }
  return $html ;
}

function EmailPrint($mail){
  $headerInfo=$mail['headerInfo'];
  $html = '<h4>' . htmlentities($headerInfo->subject) . '</h4>';
  $html .= '<p>From: ' . htmlentities($headerInfo->fromaddress) . '</p>';
  $html .= '<p>To: ' . htmlentities($headerInfo->toaddress) . '</p>';
  $html .= '<div style="background: lightgrey">' . (empty($mail['htmlText']) ? ('<p>' . $mail['plainText'] . '</p>') : $mail['htmlText']) . '</div>';
  return $html ;
}

function EmailDownload($host, $user, $password){
  $html = '<h3>Simple imap client</h3>';
  $mails=EmailGetAll($host, $user, $password);
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
