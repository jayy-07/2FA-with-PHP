<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 
include '../settings/connection.php';

session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['email'])) {
        $response['message'] = "Session expired. Please login again.";
    } elseif (!isset($_SESSION['otp_time']) || (time() - $_SESSION['otp_time'] > 90)) { // 1.5 minute cooldown
        // Generate new OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();

        $email = $_SESSION['email'];

        // Send OTP via email
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp-relay.brevo.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '7d5a30001@smtp-brevo.com';
            $mail->Password   = '3FTNs4jWhIx5yDXA';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('joelkodji123@gmail.com', 'Joel and Lovette');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Your New OTP for Login';
            $mail->Body    = "Your new OTP is: <b>{$otp}</b>. It will expire in 10 minutes.";
            $mail->AltBody = "Your new OTP is: {$otp}. It will expire in 10 minutes.";

            $mail->send();
            $response['success'] = true;
            $response['message'] = 'New OTP sent to your email.';
        } catch (Exception $e) {
            $response['message'] = "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $remainingTime = 90 - (time() - $_SESSION['otp_time']);
        $response['message'] = "Please wait " . $remainingTime . " seconds before requesting a new OTP.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>