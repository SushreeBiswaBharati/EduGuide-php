<?php

// Prevent duplicate function declaration
if (function_exists('sendMail')) {
    return;
}


// Load mail configuration
require_once __DIR__ . '/mailer_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

/**
 * Send Email using PHPMailer SMTP
 *
 * @param string $to
 * @param string $toName
 * @param string $subject
 * @param string $body
 * @return true|string
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
            MAIL_ENCRYPTION === 'ssl'
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;

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