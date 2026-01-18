<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['place_order'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$total = $_POST['total_price'];
$paymentMethod = $_POST['payment_method']; // "COD"

// Start transaction
mysqli_begin_transaction($con);

try {
    // Insert order
    $orderSql = "INSERT INTO orders (user_id, total_price, status, payment_method) 
                 VALUES ($userId, $total, 'Pending', '$paymentMethod')";
    
    if (mysqli_query($con, $orderSql)) {
        $order_id = mysqli_insert_id($con);
        
        // Fetch cart items
        $cartSql = "SELECT c.*, p.selling_price FROM cart c 
                    JOIN products p ON c.product_id = p.id 
                    WHERE c.user_id = $userId";
        $cartResult = mysqli_query($con, $cartSql);
        
        // Add order items
        while ($row = mysqli_fetch_assoc($cartResult)) {
            $productId = $row['product_id'];
            $quantity = $row['quantity'];
            $price = $row['selling_price'];
            
            $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                        VALUES ($order_id, $productId, $quantity, $price)";
            mysqli_query($con, $itemSql);
        }
        
        // Clear cart
        $clearCartSql = "DELETE FROM cart WHERE user_id = $userId";
        mysqli_query($con, $clearCartSql);
        
        // Commit transaction
        mysqli_commit($con);
        
        // Store in session to show on thankyou page
        $_SESSION['order_id'] = $order_id;
        $_SESSION['order_total'] = $total;
        
        header("Location: ../thankyou.php");
        exit();
    } else {
        throw new Exception("Failed to create order");
    }
} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($con);
    $_SESSION['payment_error'] = "Order processing failed: " . $e->getMessage();
    header("Location: ../checkout.php");
    exit();
}
?>
