<?php
session_start();
require 'config.php';

$email = $_SESSION['reset_email'];
$otp = $_POST['otp'];

$stmt = $con->prepare("SELECT reset_token, token_expiry FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($storedOtp, $expiry);
$stmt->fetch();

if ($storedOtp === $otp && strtotime($expiry) >= time()) {
    $_SESSION['otp_verified'] = true;
    header("Location: ../reset-password.php");
} else {
    $_SESSION['otp_error'] = "Invalid or expired OTP.";
    header("Location: ../verify-otp.php");
}
