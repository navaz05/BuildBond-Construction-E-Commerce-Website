<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to highlight search terms - only declare if it doesn't already exist
if (!function_exists('highlightSearchTerm')) {
    function highlightSearchTerm($text, $searchTerm) {
        if (empty($searchTerm)) {
            return $text;
        }
        return preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<span class="highlight">$1</span>', htmlspecialchars($text));
    }
}

// Get search term from session
$searchTerm = isset($_SESSION['search_term']) ? $_SESSION['search_term'] : '';

// Get product reviews
?>

<div class="col-md-4 mb-4">
    <div class="product-card p-3 border rounded shadow-sm text-center bg-white">
        <img src="uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid mb-2" style="height: 200px; object-fit: contain;">
        <h5><?= highlightSearchTerm($product['name'], $searchTerm) ?></h5>
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
                <button type="submit" class="btn" style="background-color: #05a39c; color:white;">Add to Cart</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn" style="background-color: #05a39c; color:white;">Login to Add</a>
        <?php endif; ?>

        <div class="product-details mt-3 p-3 bg-light rounded d-none" id="details-<?= $product['id'] ?>">
            <p><strong>Product Details:</strong> <?= highlightSearchTerm($product['description'], $searchTerm) ?></p>

            <hr>
            <p><strong>Customer Reviews:</strong></p>

            <?php
            $productId = $product['id'];
            // Get average rating
            $avgRatingQuery = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = $productId";
            $avgRatingResult = mysqli_query($con, $avgRatingQuery);
            $avgRatingData = mysqli_fetch_assoc($avgRatingResult);
            $avgRating = round($avgRatingData['avg_rating'], 1);
            $totalReviews = $avgRatingData['total_reviews'];
            
            // Get only one review (the most recent one)
            $reviewSql = "SELECT * FROM reviews WHERE product_id = $productId ORDER BY created_at DESC LIMIT 1";
            $reviewResult = mysqli_query($con, $reviewSql);

            if (mysqli_num_rows($reviewResult) > 0) {
                // Display average rating
                echo "<div class='mb-2'>";
                echo "<div class='d-flex align-items-center'>";
                echo "<span class='me-2'>Average Rating: $avgRating</span>";
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $avgRating) {
                        echo "<i class='bi bi-star-fill text-warning'></i>";
                    } elseif ($i - 0.5 <= $avgRating) {
                        echo "<i class='bi bi-star-half text-warning'></i>";
                    } else {
                        echo "<i class='bi bi-star text-warning'></i>";
                    }
                }
                echo "<span class='ms-2'>($totalReviews reviews)</span>";
                echo "</div>";
                echo "</div>";
                
                // Display the most recent review
                $review = mysqli_fetch_assoc($reviewResult);
                ?>
                <div class="text-start mb-2">
                    <strong><?= htmlspecialchars($review['user_name']) ?></strong> 
                    <span class="text-warning">
                        <?php for ($i = 0; $i < $review['rating']; $i++) echo '★'; ?>
                        <?php for ($i = $review['rating']; $i < 5; $i++) echo '☆'; ?>
                    </span>
                    <p class="mb-1"><?= highlightSearchTerm($review['comment'], $searchTerm) ?></p>
                    <small class="text-muted"><?= date('d M Y, h:i A', strtotime($review['created_at'])) ?></small>
                </div>
                
                <?php if ($totalReviews > 1): ?>
                    <a href="product_reviews.php?id=<?= $productId ?>" class="btn btn-sm text-gmain">See All Reviews</a>
                <?php endif; ?>
                
                <hr>
                <?php
            } else {
                echo "<p class='text-muted'>No reviews yet.</p>";
            }
            ?>
        </div>
    </div>
</div>

<style>
    .text-gmain {
        color: #05a39c;
    }
</style>