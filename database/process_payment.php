<?php
session_start();
include '../includes/razorpay_config.php';
include 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$success = false;
$error = null;

// Log the incoming request
error_log("Payment processing started for user ID: $userId");
error_log("GET parameters: " . print_r($_GET, true));

try {
    // Check if all required parameters are present
    if (!isset($_GET['razorpay_order_id']) || !isset($_GET['razorpay_payment_id']) || !isset($_GET['razorpay_signature'])) {
        throw new Exception("Missing payment parameters");
    }
    
    // Verify payment signature
    $attributes = array(
        'razorpay_order_id' => $_GET['razorpay_order_id'],
        'razorpay_payment_id' => $_GET['razorpay_payment_id'],
        'razorpay_signature' => $_GET['razorpay_signature']
    );
    
    error_log("Verifying payment signature with attributes: " . print_r($attributes, true));
    $api->utility->verifyPaymentSignature($attributes);
    error_log("Payment signature verified successfully");
    
    // Start transaction
    mysqli_begin_transaction($con);

    try {
        // Get cart items
        $cartSql = "SELECT c.*, p.selling_price FROM cart c 
                    JOIN products p ON c.product_id = p.id 
                    WHERE c.user_id = $userId";
        $cartResult = mysqli_query($con, $cartSql);
        
        if (!$cartResult) {
            throw new Exception("Error fetching cart items: " . mysqli_error($con));
        }
        
        $cartItems = [];
        if ($cartResult) {
            while ($row = mysqli_fetch_assoc($cartResult)) {
                $cartItems[] = $row;
            }
        }
        
        if (empty($cartItems)) {
            throw new Exception("Cart is empty");
        }
        
        error_log("Found " . count($cartItems) . " items in cart");

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['selling_price'] * $item['quantity'];
        }
        
        // Create order record
        $razorpayOrderId = $_GET['razorpay_order_id'];
        $razorpayPaymentId = $_GET['razorpay_payment_id'];
        
        $orderSql = "INSERT INTO orders (user_id, total_price, payment_method, status, razorpay_order_id, razorpay_payment_id) 
                     VALUES ($userId, $total, 'Razorpay', 'Processing', '$razorpayOrderId', '$razorpayPaymentId')";
        
        error_log("Executing order SQL: $orderSql");
        
        if (mysqli_query($con, $orderSql)) {
            $orderId = mysqli_insert_id($con);
            error_log("Order created successfully with ID: $orderId");
            
            // Add order items
            foreach ($cartItems as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['selling_price'];
                
                $orderItemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                VALUES ($orderId, $productId, $quantity, $price)";
                error_log("Executing order item SQL: $orderItemSql");
                
                if (!mysqli_query($con, $orderItemSql)) {
                    throw new Exception("Error adding order item: " . mysqli_error($con));
                }
            }
            
            // Clear cart
            $clearCartSql = "DELETE FROM cart WHERE user_id = $userId";
            error_log("Clearing cart with SQL: $clearCartSql");
            
            if (!mysqli_query($con, $clearCartSql)) {
                throw new Exception("Error clearing cart: " . mysqli_error($con));
            }
            
            // Commit transaction
            mysqli_commit($con);
            error_log("Transaction committed successfully");
            
            $success = true;
            $_SESSION['order_success'] = true;
            $_SESSION['order_id'] = $orderId;
            $_SESSION['order_total'] = $total;
            
            error_log("Redirecting to thankyou.php");
            header("Location: ../thankyou.php");
            exit();
        } else {
            throw new Exception("Failed to create order: " . mysqli_error($con));
        }
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($con);
        error_log("Transaction rolled back due to error: " . $e->getMessage());
        $error = "Order processing failed: " . $e->getMessage();
    }
} catch (Exception $e) {
    error_log("Payment verification failed: " . $e->getMessage());
    $error = "Payment verification failed: " . $e->getMessage();
}

if (!$success) {
    error_log("Payment failed with error: $error");
    $_SESSION['payment_error'] = $error;
    header("Location: ../checkout.php");
    exit();
}
?> 