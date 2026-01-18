<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_id'])) {
    $cartId = intval($_POST['cart_id']);
    $userId = $_SESSION['user_id'];
    
    $sql = "DELETE FROM cart WHERE id = $cartId AND user_id = $userId";
    mysqli_query($con, $sql);
}

header("Location: ../cart.php");
exit();
