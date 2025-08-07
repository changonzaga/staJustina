<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('sendEmail')) {
    function sendEmail($mailConfig) {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;                      // Disable verbose debug output
            $mail->isSMTP();                           // Use SMTP
            $mail->Host       = env('EMAIL_HOST');     // SMTP server
            $mail->SMTPAuth   = true;                  // Enable SMTP authentication
            $mail->Username   = env('EMAIL_USERNAME'); // SMTP username
            $mail->Password   = env('EMAIL_PASSWORD'); // SMTP password
            $mail->SMTPSecure = env('EMAIL_ENCRYPTION'); // Encryption (e.g., tls)
            $mail->Port       = env('EMAIL_PORT');     // SMTP port
            $mail->setFrom($mailConfig['mail_from_email'], $mailConfig['mail_from_name']);
            $mail->addAddress($mailConfig['mail_recipient_email'], $mailConfig['mail_recipient_name']);
            $mail->isHTML(true);                       // Send as HTML
            $mail->Subject = $mailConfig['mail_subject'];
            $mail->Body    = $mailConfig['mail_body'];
            if($mail->send()) {
                return true;
            } else {
                return false;
            }
    }
}
