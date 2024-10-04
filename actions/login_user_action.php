<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../settings/connection.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    $query = "SELECT * FROM users WHERE email = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) { 
            session_start();
            $_SESSION['user_id'] = $user['userid'];
            $_SESSION['email'] = $user['email'];

            // Generate OTP
            $otp = sprintf("%06d", mt_rand(1, 999999));
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = time();

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
                $mail->addAddress($email, $user['name']);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Login';
                $mail->Body    = "Your OTP is: <b>{$otp}</b>. It will expire in 10 minutes.";
                $mail->AltBody = "Your OTP is: {$otp}. It will expire in 10 minutes.";

                $mail->send();
                $response['success'] = true;
                $response['message'] = 'OTP sent to your email.';
            } catch (Exception $e) {
                $response['message'] = "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $response['message'] = "Incorrect password.";
        }
    } else {
        $response['message'] = "Account does not exist.";
    }

    echo json_encode($response);
}
?>