<?php

require __DIR__ . '/../vendor/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $subject, $body) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'a.saivikas18@gmail.com';
        $mail->Password   = 'lexozizdzzfccxuw';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('YOUR_GMAIL@gmail.com', 'Campus Booking');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        return $mail->send();

    } catch (Exception $e) {
        return false;
    }
}


