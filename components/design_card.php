<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="col-md-4 mb-4 " >
    <div class="product-card p-3 border rounded shadow-sm text-center bg-white">
        <img src="uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid mb-2" style="height: 200px; object-fit: contain;">
        <h5><?= $product['name'] ?></h5>
        <p>
            MRP: <span class="text-decoration-line-through text-muted">₹<?= $product['mrp'] ?></span>  
            <span class="text-danger fw-bold">₹<?= $product['selling_price'] ?></span>  
            <span class="text-success">
                (<?= round((($product['mrp'] - $product['selling_price']) / $product['mrp']) * 100, 2) ?>% OFF)
            </span>
        </p>

        <button class="btn  view-btn" data-id="<?= $product['id'] ?>" style="border-color: #05a39c;">View</button>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="database/add_to_cart.php" method="POST" class="d-inline">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" class="btn" style="background-color: #05a39c; color:white;"> Add to Cart</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn" style="background-color: #05a39c; color:white;">Login to Add</a>
        <?php endif; ?>

        <div class="product-details mt-3 p-3 bg-light rounded d-none" id="details-<?= $product['id'] ?>">
            <p><strong>Product Details:</strong> <?= $product['description'] ?></p>

            <hr>
            <p><strong>Customer Reviews:</strong></p>

            <?php
            $productId = $product['id'];
            $reviewSql = "SELECT * FROM reviews WHERE product_id = $productId ORDER BY created_at DESC";
            $reviewResult = mysqli_query($con, $reviewSql);

            if (mysqli_num_rows($reviewResult) > 0) {
                while ($review = mysqli_fetch_assoc($reviewResult)) {
                    ?>
                    <div class="text-start mb-2">
                        <strong><?= htmlspecialchars($review['user_name']) ?></strong> 
                        <span class="text-warning">
                            <?php for ($i = 0; $i < $review['rating']; $i++) echo '★'; ?>
                            <?php for ($i = $review['rating']; $i < 5; $i++) echo '☆'; ?>
                        </span>
                        <p class="mb-1"><?= htmlspecialchars($review['comment']) ?></p>
                        <small class="text-muted"><?= date('d M Y, h:i A', strtotime($review['created_at'])) ?></small>
                    </div>
                    <hr>
                    <?php
                }
            } else {
                echo "<p class='text-muted'>No reviews yet.</p>";
            }
            ?>
        </div>
    </div>
</div>