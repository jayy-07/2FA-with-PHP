<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submitted_otp = $_POST['otp'];

    if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_time'])) {
        $response['message'] = "OTP session has expired. Please login again.";
    } elseif (time() - $_SESSION['otp_time'] > 600) { // 10 minutes expiration
        $response['message'] = "OTP has expired. Please request a new one.";
        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
    } elseif ($submitted_otp == $_SESSION['otp']) {
        $response['success'] = true;
        $response['message'] = "Verification successful";
        
        // Set a flag in the session to indicate the user is fully authenticated
        $_SESSION['authenticated'] = true;
        
        // Clear the OTP from the session
        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
        
        // could potentially add option to handle last login time, etc...
        
    } else {
        $response['message'] = "Incorrect OTP. Please try again.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>