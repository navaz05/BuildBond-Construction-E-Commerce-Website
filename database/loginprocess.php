<?php
session_start();
require 'config.php';

$error_type = ""; // Can be: "email_not_found" or "invalid_password"

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (isset($con)) {
        $stmt = $con->prepare("SELECT id, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;

                // ✅ Redirect based on role
                if ($role === 'admin') {
                    header("Location: ../admin/admin_dashboard.php");
                } else {
                    header("Location: ../dash.php");
                }
                exit();
            } else {
                $error_type = "invalid_password";
            }
        } else {
            $error_type = "email_not_found";
        }
        $stmt->close();
    }
    $con->close();
}

// Store error type
$_SESSION['signin_error_type'] = $error_type;
$_SESSION['email_attempt'] = $email; // Optional: to refill email input
header("Location: ../login.php");
exit();
?>
