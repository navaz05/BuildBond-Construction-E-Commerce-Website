<?php
include 'navbar-top.php';
include 'database/config.php';

// Get search query if exists
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

// Check if a specific product ID is requested
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get category parameter (should always be 'design' for this page)
$category = isset($_GET['category']) ? mysqli_real_escape_string($con, $_GET['category']) : 'design';

// Modify SQL query based on search or specific product
if ($productId > 0) {
    // If a specific product is requested
    $sql = "SELECT * FROM products WHERE id = $productId AND category = 'design'";
    $result = mysqli_query($con, $sql);
    
    // If product found, set search term to product name for highlighting
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        $search = $product['name'];
        // Reset result pointer
        mysqli_data_seek($result, 0);
    }
} else if (!empty($search)) {
    // If search query exists
    $sql = "SELECT * FROM products WHERE category = 'design' AND (name LIKE '%$search%' OR description LIKE '%$search%')";
    $result = mysqli_query($con, $sql);
} else {
    // Default query - all design products
    $sql = "SELECT * FROM products WHERE category = 'design'";
    $result = mysqli_query($con, $sql);
}

// Store search term in session for highlighting
$_SESSION['search_term'] = $search;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .highlight {
        background-color: #ffff99;
        padding: 2px;
        border-radius: 3px;
    }
</style>

<div class="container mt-4">
    <?php if (!empty($search)): ?>
        <h2 class="mb-4">Search Results for: "<?php echo htmlspecialchars($search); ?>"</h2>
    <?php endif; ?>
    <div class="row">
        <?php 
        if ($result && mysqli_num_rows($result) > 0) {
            while ($product = mysqli_fetch_assoc($result)) {
                include 'components/product_card.php';
            }
        } else {
            echo '<div class="col-12 text-center"><h3>No designs found matching your search.</h3></div>';
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(".view-btn").click(function () {
            var id = $(this).data("id");
            $("#details-" + id).toggleClass("d-none");
        });
        
        // If a specific product was requested, scroll to it
        <?php if ($productId > 0): ?>
        setTimeout(function() {
            $('html, body').animate({
                scrollTop: $('.product-card').first().offset().top - 100
            }, 500);
        }, 100);
        <?php endif; ?>
    });
</script>
