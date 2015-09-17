<?php
function getParameter($parameter){
  return isset($_GET[$parameter]) ? $_GET[$parameter] : "";
}
$to=getParameter('to');
if (empty($to)){
  echo 'To call: http://host/testmail.php?to=mail@domain&subject=header&message=body';
} else {
  $subject=getParameter('subject');
  $message=getParameter('message');
  echo "sending mail to $to with subject $subject and message $message<br>";
  $result=mail($to, $subject, $message);
  echo "result=$result";
}
