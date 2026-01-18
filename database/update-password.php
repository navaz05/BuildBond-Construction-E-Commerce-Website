<?php
session_start();
require 'config.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['reset_email'];
$password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

// Update password
$stmt = $con->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
$stmt->bind_param("ss", $password, $email);
$stmt->execute();

// Cleanup session
unset($_SESSION['reset_email'], $_SESSION['otp_verified']);

header("Location: ../login.php?reset=success");
?>
