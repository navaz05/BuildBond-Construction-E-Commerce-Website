<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';
require '../includes/mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



try {
    // Get and validate email
    $email = trim($_POST['email']);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address.");
    }

    // Check if email exists in DB
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['forgot_error'] = "No account found with that email address.";
        header("Location: ../forgot-password.php");
        exit();
    }

    // Generate OTP and expiry
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Store OTP and expiry in DB
    $stmt = $con->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp, $expiry, $email);
    $stmt->execute();

    // Get configured mailer
    $mail = getConfiguredMailer();
    $mail->addAddress($email);
    $mail->Subject = 'OTP to Reset Your Password';
    $mail->Body = "
        <h3>Password Reset OTP</h3>
        <p>Your One-Time Password (OTP) is: <strong>$otp</strong></p>
        <p>This OTP is valid for 10 minutes.</p>
    ";

    $mail->send();

    $_SESSION['reset_email'] = $email;
    header("Location: ../verify-otp.php");
    exit();
} catch (Exception $e) {
    $_SESSION['forgot_error'] = "Failed to send OTP. " . $e->getMessage();
    header("Location: ../forgot-password.php");
    exit();
}
