<?php
require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
require '/usr/share/php/libphp-phpmailer/class.smtp.php';
$mail = new PHPMailer;
$mail->setFrom('learningcenter.kemenkumham@gmail.com');
$mail->addAddress('baysunnyway@gmail.com');
$mail->Subject = 'Message sent by PHPMailer';
$mail->Body = 'apaan nih';
$mail->IsSMTP();
$mail->SMTPSecure = 'ssl';
$mail->Host = 'ssl://smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Port = 465;

//Set your existing gmail address as user name
$mail->Username = "learningcenter.kemenkumham@gmail.com";

//Set the password of your gmail address here
$mail->Password = 'baysunny17';
$mail->send();
// if(!$mail->send()) {
//   echo 'Email is not sent.';
//   echo 'Email error: ' . $mail->ErrorInfo;
// } else {
//   echo 'Email has been sent.';
// }
