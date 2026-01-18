<?php
session_start();

require "config.php";

$error = $success = '';

if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['signup']))
{
    $username = trim($_POST['name']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($password !== $confirm_password){
        $error = "Password does not match";
    }
    else{
        // Check for duplicate email
        $emailQuery = $con->prepare("SELECT id FROM users WHERE email=?");
        $emailQuery->bind_param('s',$email);
        $emailQuery->execute();
        $emailQuery->store_result();
        
        // Check for duplicate username
        $nameQuery = $con->prepare("SELECT id FROM users WHERE name=?");
        $nameQuery->bind_param('s',$username);
        $nameQuery->execute();
        $nameQuery->store_result();
        
        if ($emailQuery->num_rows > 0){
            $error = "An account with this email already exists";
        }
        else if ($nameQuery->num_rows > 0){
            $error = "This username is already taken. Please choose a different name";
        }
        else{
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $insert = $con->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
            $insert->bind_param('sss',$username,$email,$hashed_password);
            if($insert->execute()){
                // Send confirmation email
                $to = $email;
                $subject = "Welcome to BuildBond - Registration Confirmation";
                $message = "Dear " . $username . ",\n\n";
                $message .= "Thank you for registering with BuildBond!\n\n";
                $message .= "Your account has been successfully created.\n";
                $message .= "You can now login to your account using your email and password.\n\n";
                $message .= "Best regards,\nBuildBond Team";
                
                $headers = "From: BuildBond <noreply@buildbond.com>\r\n";
                $headers .= "Reply-To: support@buildbond.com\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();
                
                if(mail($to, $subject, $message, $headers)) {
                    $success = "Sign up successful! A confirmation email has been sent to your email address. You can now <a href='login.php'>sign in</a>";
                } else {
                    $success = "Sign up successful! However, we couldn't send the confirmation email. You can still <a href='login.php'>sign in</a>";
                }
            }
            else{
                $error = 'An error occurred, please try again.';
            }
            $insert->close();
        }
        $emailQuery->close();
        $nameQuery->close();
    }
    $con->close();

    $_SESSION['signup_error'] = $error;
    $_SESSION['signup_success'] = $success;
    header('location: ../register.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>registered</title>
</head>
<body>
  <h1>regidtration is done</h1>
</body>
</html>