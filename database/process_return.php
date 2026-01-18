<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

session_start();
include 'config.php';
require '../includes/mail_config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['order_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$orderId = $_POST['order_id'];

// Verify order belongs to user and is delivered
$orderQuery = "SELECT * FROM orders WHERE id = $orderId AND user_id = $userId AND status = 'Delivered'";
$orderResult = mysqli_query($con, $orderQuery);

if (mysqli_num_rows($orderResult) == 0) {
    $_SESSION['error'] = "Invalid order or order not eligible for return.";
    header("Location: ../order-details.php?order_id=" . $orderId);
    exit();
}

$order = mysqli_fetch_assoc($orderResult);

// Update order status to Returned
$updateQuery = "UPDATE orders SET status = 'Returned' WHERE id = $orderId";
if (mysqli_query($con, $updateQuery)) {
    // Get user email for notification
    $userQuery = "SELECT email, name FROM users WHERE id = $userId";
    $userResult = mysqli_query($con, $userQuery);
    $userData = mysqli_fetch_assoc($userResult);
    
    try {
        // Get configured mailer with debug disabled
        $mail = getConfiguredMailer(false);
        $mail->addAddress($userData['email']);
        $mail->Subject = "Order Return Confirmation - BuildBond";
        
        // Create HTML message
        $mail->Body = "
        <html>
        <head>
            <title>Order Return Confirmation</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;'>
            <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <h2 style='color: #4CAF50; text-align: center;'>Order Return Confirmation</h2>
                <p style='font-size: 16px; color: #333;'>Dear <strong>" . htmlspecialchars($userData['name']) . "</strong>,</p>
                <p style='font-size: 16px; color: #555;'>We have successfully received your return request for <strong>Order #" . $orderId . "</strong>.</p>
                <p style='font-size: 16px; color: #555;'>Our team will arrange a pickup from your provided address soon.</p>
                <p style='font-size: 16px; color: #555;'>You can expect your refund to be processed within <strong>3 working days</strong>.</p>
                <div style='margin: 30px 0; text-align: center;'>
                    <a href='#' style='background-color: #4CAF50; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Track Return Status</a>
                </div>
                <p style='font-size: 16px; color: #333;'>Thank you for choosing <strong>BuildBond</strong>!</p>
                <br>
                <p style='font-size: 14px; color: #777;'>Best regards,<br>BuildBond Team</p>
            </div>
        </body>
        </html>
        ";
        
        
        // Send email
        $mail->send();
        $_SESSION['success'] = "Return request submitted successfully. You will receive an email confirmation shortly.";
    } catch (Exception $e) {
        $_SESSION['success'] = "Return request submitted successfully. However, we couldn't send the confirmation email: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Failed to process return request. Please try again.";
}

header("Location: ../order-details.php?order_id=" . $orderId);
exit(); 