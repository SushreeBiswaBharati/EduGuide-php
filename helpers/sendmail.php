<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Correct PHPMailer paths
require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';

// Load mail configuration
require_once __DIR__ . '/mailer_config.php';

/**
 * Send Email using PHPMailer SMTP
 */
function sendMail($to, $toName, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {

        // SMTP Configuration
        $mail->isSMTP();

        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;

        $mail->SMTPSecure =
             PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = MAIL_PORT;

        // Sender
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);

        // Recipient
        $mail->addAddress($to, $toName);

        // Email format
        $mail->isHTML(true);

        // Subject
        $mail->Subject = $subject;

        // Body
        $mail->Body = $body;

        // Plain text fallback
        $mail->AltBody = strip_tags($body);

        // Send mail
        $mail->send();

        return true;

    } catch (Exception $e) {

        error_log("EduGuide Mail Error: " . $mail->ErrorInfo);

        return $mail->ErrorInfo;
    }
}
?>