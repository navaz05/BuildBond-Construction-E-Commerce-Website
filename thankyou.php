<?php
include 'navbar-top.php';
include 'database/config.php';

if (!isset($_SESSION['order_id'])) {
    header("Location: index.php");
    exit();
}

$orderId = $_SESSION['order_id'];
$total = isset($_SESSION['order_total']) ? $_SESSION['order_total'] : 0;

// Get payment method from database
$paymentMethod = "Cash on Delivery"; // Default
if (isset($con)) {
    $sql = "SELECT payment_method FROM orders WHERE id = $orderId";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);
        $paymentMethod = $order['payment_method'];
    }
}
?>

<style>
    .thankyou-container {
        background-color: #f9f9f9;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
    }
    .thankyou-container h3 {
        color: #05a39c;
        font-weight: bold;
    }
    .thankyou-container .btn-primary {
        background-color: #05a39c;
        border-color: #05a39c;
    }
    .thankyou-container .btn-primary:hover {
        background-color: #048a83;
        border-color: #048a83;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="thankyou-container text-center">
        <h3>🎉 Thank you for your order!</h3>
        <p class="lead">We truly appreciate your trust in <strong>BuildBond</strong> 💚</p>
        <p>Your Order ID is <strong>#<?= $orderId ?></strong></p>
        <p>Total Amount: <strong>₹<?= number_format($total, 2) ?></strong></p>
        <p>Payment Mode: <strong><?= htmlspecialchars($paymentMethod) ?></strong></p>

        <hr>

        <p class="text-muted mb-4">🛠️ Our team is now preparing your order with care. You will receive a notification once it's out for delivery.</p>

        <a href="myorders.php" class="btn btn-primary">🚚 Track My Orders</a>
        <br><br>
        <p class="mt-4 text-secondary">Need help? <a href="contact.php">Contact our support team</a></p>
    </div>
</div>
