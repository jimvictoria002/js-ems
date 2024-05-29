<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../../vendor/autoload.php';

$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'jimuelhayan@gmail.com';
$mail->Password = 'snnkqzuhprggizzl'; 
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('jimuelhayan@gmail.com', 'Event Management System'); 


?>
