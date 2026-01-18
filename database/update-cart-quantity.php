<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cartId = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    $userId = $_SESSION['user_id'];
    
    // Validate quantity (must be at least 1)
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    // Update quantity
    $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iii", $quantity, $cartId, $userId);
    $stmt->execute();
}

header("Location: ../cart.php");
exit(); 