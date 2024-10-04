<?php
// Load Composer's autoloader (assuming you've already run `composer require phpmailer/phpmailer`)
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send emails
function sendOTPEmail($recipientEmail, $otpCode) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';  // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = '7d5a30001@smtp-brevo.com';  // SMTP login
        $mail->Password   = '3FTNs4jWhIx5yDXA';  // SMTP password (replace with the actual password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('7d5a30001@smtp-brevo.com', 'Joel and Lovette');  // Sender's email and name
        $mail->addAddress($recipientEmail);  // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Your One-Time Password (OTP) is: <b>' . $otpCode . '</b>. It will expire in 5 minutes.';

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}
