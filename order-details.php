<?php
include 'navbar-top.php';
include 'database/config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: login.php");
    exit();
}

$orderId = $_GET['order_id'];
$userId = $_SESSION['user_id'];

// Fetch order using direct mysqli query
$orderQuery = "SELECT * FROM orders WHERE id = $orderId AND user_id = $userId";
$orderResult = mysqli_query($con, $orderQuery);

if (!$orderResult || mysqli_num_rows($orderResult) == 0) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Order not found.</div></div>";
    exit();
}

$order = mysqli_fetch_assoc($orderResult);

// Fetch order items using direct mysqli query
$itemQuery = "
    SELECT oi.*, p.name, p.image 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = $orderId
";
$itemsResult = mysqli_query($con, $itemQuery);

// Get user name for reviews
$userQuery = "SELECT name FROM users WHERE id = $userId";
$userResult = mysqli_query($con, $userQuery);
$userData = mysqli_fetch_assoc($userResult);
$userName = $userData['name'];

// Check if a review was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $productId = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    
    // Debug information
    $debugInfo = "Product ID: $productId, Rating: $rating, User: $userName";
    
    // Check if review already exists
    $checkQuery = "SELECT id FROM reviews WHERE product_id = $productId AND user_name = '$userName'";
    $checkResult = mysqli_query($con, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Update existing review
        $updateQuery = "UPDATE reviews SET rating = $rating, comment = '$comment' WHERE product_id = $productId AND user_name = '$userName'";
        $updateResult = mysqli_query($con, $updateQuery);
        if ($updateResult) {
            $reviewMessage = "Review updated successfully!";
        } else {
            $reviewMessage = "Error updating review: " . mysqli_error($con);
        }
    } else {
        // Insert new review
        $insertQuery = "INSERT INTO reviews (product_id, user_name, rating, comment, created_at) VALUES ($productId, '$userName', $rating, '$comment', NOW())";
        $insertResult = mysqli_query($con, $insertQuery);
        if ($insertResult) {
            $reviewMessage = "Review submitted successfully!";
        } else {
            $reviewMessage = "Error submitting review: " . mysqli_error($con);
        }
    }
    
    // Add debug information to the message
    $reviewMessage .= " ($debugInfo)";
}
?>

<div class="container mt-4">
    <h4 class="fw-bold mb-3">Order Details (#<?= $order['id'] ?>)</h4>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <p><strong>Order Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
    <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
    <p><strong>Status:</strong> <?= $order['status'] ?></p>

    <?php if ($order['status'] === 'Delivered'): ?>
        <form action="database/process_return.php" method="POST" class="mb-3">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to return this order?')">
                Return Order
            </button>
        </form>
    <?php endif; ?>

    <?php if (isset($reviewMessage)): ?>
        <div class="alert alert-success"><?= $reviewMessage ?></div>
    <?php endif; ?>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <?php if ($order['status'] === 'Delivered'): ?>
                    <th class="text-gmain">Review</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($itemsResult)): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><img src="uploads/<?= $item['image'] ?>" width="50"></td>
                    <td>₹<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    <?php if ($order['status'] === 'Delivered'): ?>
                        <td>
                            <?php
                            // Check if user has already reviewed this product
                            $reviewQuery = "SELECT * FROM reviews WHERE product_id = {$item['product_id']} AND user_name = '$userName'";
                            $reviewResult = mysqli_query($con, $reviewQuery);
                            $hasReviewed = mysqli_num_rows($reviewResult) > 0;
                            $review = $hasReviewed ? mysqli_fetch_assoc($reviewResult) : null;
                            ?>
                            
                            <?php if ($hasReviewed): ?>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi <?= $i <= $review['rating'] ? 'bi-star-fill text-warning' : 'bi-star text-warning' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="mb-0"><?= htmlspecialchars($review['comment']) ?></p>
                                    <small class="text-muted">Posted on <?= date('d M Y', strtotime($review['created_at'])) ?></small>
                                </div>
                            <?php endif; ?>
                            
                            <button type="button" class="btn btn-sm <?= $hasReviewed ? 'text-gmain' : 'text-gmain' ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#reviewModal<?= $item['product_id'] ?>">
                                <?= $hasReviewed ? 'Edit Review' : 'Write Review' ?>
                            </button>
                            
                            <!-- Review Modal -->
                            <div class="modal fade" id="reviewModal<?= $item['product_id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Review for <?= htmlspecialchars($item['name']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Rating</label>
                                                    <div class="rating">
                                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                                            <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>_<?= $item['product_id'] ?>" 
                                                                   <?= ($hasReviewed && $review['rating'] == $i) ? 'checked' : '' ?> required>
                                                            <label for="star<?= $i ?>_<?= $item['product_id'] ?>"><i class="bi bi-star-fill"></i></label>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="comment<?= $item['product_id'] ?>" class="form-label">Your Review</label>
                                                    <textarea class="form-control" id="comment<?= $item['product_id'] ?>" name="comment" rows="3" required><?= $hasReviewed ? htmlspecialchars($review['comment']) : '' ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="submit_review" class="btn btn-secondary">Submit Review</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h5 class="text-end">Total Amount: ₹<?= number_format($order['total_price'], 2) ?></h5>
</div>

<style>
    .text-gmain {
        color: #05a39c;
    }
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    
    .rating input {
        display: none;
    }
    
    .rating label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        margin: 0 2px;
    }
    
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107;
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
