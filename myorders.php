<?php
include 'navbar-top.php';
include 'database/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Use direct mysqli query instead of prepared statement
$sql = "SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC";
$result = mysqli_query($con, $sql);
?>

<div class="container mt-4">
    <h3 class="fw-bold mb-3">My Orders</h3>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td>₹<?= number_format($order['total_price'], 2) ?></td>
                            <td><?= htmlspecialchars($order['payment_method']) ?></td>
                            <td><?= $order['status'] ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
   
                            <td><a href="order-details.php?order_id=<?= $order['id'] ?>" class="btn btn-sm" style=" border-color: #05a39c;">View</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">You have not placed any orders yet.</div>
    <?php endif; ?>
</div>
