<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in
    header("Location: ../login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Verify if user exists
$user_check = "SELECT id FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $user_check);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($user_result) === 0) {
    // User doesn't exist in database
    session_destroy();
    header("Location: ../login.php?error=invalid_user");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Verify if product exists
    $product_check = "SELECT id FROM products WHERE id = ?";
    $stmt = mysqli_prepare($con, $product_check);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $product_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($product_result) === 0) {
        header("Location: ../product.php?error=invalid_product");
        exit();
    }

    // Check if product already in cart
    $check = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($con, $check);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // Product already in cart: update quantity
        $update = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $stmt = mysqli_prepare($con, $update);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    } else {
        // New item to cart
        $insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
        $stmt = mysqli_prepare($con, $insert);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    }

    // Redirect back or to cart page
    header("Location: ../cart.php");
    exit();
} else {
    // Invalid access
    header("Location: ../index.php");
    exit();
}
