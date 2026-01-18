<?php 
session_start();
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../editprofile.php");
    exit();
}

$error = $success = "";
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match!";
    } else {
        // Fetch the current password from the database
        $query = $con->prepare("SELECT password FROM users WHERE id = ?");
        $query->bind_param("i", $user_id);
        $query->execute();
        $query->store_result();
        
        if ($query->num_rows > 0) {
            $query->bind_result($db_password);
            $query->fetch();

            // Verify old password
            if (password_verify($old_password, $db_password)) {
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update password in database
                $update = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $hashed_password, $user_id);

                if ($update->execute()) {
                    $success = "Password updated successfully!";
                } else {
                    $error = "Error updating password. Please try again.";
                }
                $update->close();
            } else {
                $error = "Old password is incorrect!";
            }
        } else {
            $error = "User not found!";
        }

        $query->close();
    }
}

// Store messages in session
$_SESSION['edit_error'] = $error;
$_SESSION['edit_success'] = $success;

// Redirect back to edit profile page
header("Location: ../editprofile.php");
exit();
?>
