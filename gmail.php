<?php

require_once("phpmailer/class.phpmailer.php");
require_once("phpmailer/class.smtp.php");

function send_gmail($recipient, $subject, $body) {
  include_once("server_info.php");
  $server_info = get_server_info();
  if (strlen($server_info["mailer_address"]) < 4) {
    // The mailer address is clearly not right...
    return 0;
  }
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->SMTPAuth = true;
  $mail->Username = $server_info["mailer_address"];
  $mail->Password = $server_info["mailer_password"];
  $mail->From = $server_info["mailer_address"];
  $mail->FromName = $server_info["mailer_name"];
  $mail->AddAddress($recipient, "");
  $mail->WordWrap = 80;
  $mail->Host = $server_info["mailer_hostname"];
  $mail->Port = 26;
  $mail->Subject = $subject;
  $mail->Body = $body;
  return $mail->Send();
}

?>
