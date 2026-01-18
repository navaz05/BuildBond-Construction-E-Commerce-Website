<?php   
include 'database/config.php';

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$productId = (int)$_GET['id'];

// Get product details
$productQuery = "SELECT * FROM products WHERE id = $productId";
$productResult = mysqli_query($con, $productQuery);

if (mysqli_num_rows($productResult) == 0) {
    header("Location: index.php");
    exit();
}

$product = mysqli_fetch_assoc($productResult);

// Get all reviews for this product
$reviewsQuery = "SELECT * FROM reviews WHERE product_id = $productId ORDER BY created_at DESC";
$reviewsResult = mysqli_query($con, $reviewsQuery);

// Get average rating
$avgRatingQuery = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = $productId";
$avgRatingResult = mysqli_query($con, $avgRatingQuery);
$avgRatingData = mysqli_fetch_assoc($avgRatingResult);
$avgRating = round($avgRatingData['avg_rating'], 1);
$totalReviews = $avgRatingData['total_reviews'];

// Get rating distribution
$ratingDistribution = array();
for ($i = 1; $i <= 5; $i++) {
    $ratingCountQuery = "SELECT COUNT(*) as count FROM reviews WHERE product_id = $productId AND rating = $i";
    $ratingCountResult = mysqli_query($con, $ratingCountQuery);
    $ratingCountData = mysqli_fetch_assoc($ratingCountResult);
    $ratingDistribution[$i] = $ratingCountData['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?= htmlspecialchars($product['name']) ?> - BuildBond</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar-top.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid mb-3" style="max-height: 200px; object-fit: contain;">
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p class="mb-0">
                            MRP: <span class="text-decoration-line-through text-muted">₹<?= $product['mrp'] ?></span>  
                            <span class="text-danger fw-bold">₹<?= $product['selling_price'] ?></span>
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Rating Summary</h5>
                        <div class="d-flex align-items-center mb-3">
                            <h2 class="mb-0 me-2"><?= $avgRating ?></h2>
                            <div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $avgRating): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php elseif ($i - 0.5 <= $avgRating): ?>
                                        <i class="bi bi-star-half text-warning"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="ms-2">(<?= $totalReviews ?> reviews)</span>
                        </div>

                        <div class="rating-bars">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2" style="width: 60px;"><?= $i ?> stars</div>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: <?= $totalReviews > 0 ? ($ratingDistribution[$i] / $totalReviews * 100) : 0 ?>%"></div>
                                    </div>
                                    <div class="ms-2" style="width: 40px;"><?= $ratingDistribution[$i] ?></div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <h3 class="mb-4">Customer Reviews</h3>
                
                <?php if (mysqli_num_rows($reviewsResult) > 0): ?>
                    <?php while ($review = mysqli_fetch_assoc($reviewsResult)): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0"><?= htmlspecialchars($review['user_name']) ?></h5>
                                    <div class="text-warning">
                                        <?php for ($i = 0; $i < $review['rating']; $i++) echo '★'; ?>
                                        <?php for ($i = $review['rating']; $i < 5; $i++) echo '☆'; ?>
                                    </div>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($review['comment']) ?></p>
                                <small class="text-muted"><?= date('d M Y, h:i A', strtotime($review['created_at'])) ?></small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        No reviews yet for this product.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 