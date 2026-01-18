<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Hide warnings & notices
session_start();

include_once(__DIR__ . "/navbar-top.php"); // Navbar include

?>
<?php include("product.php")?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | BuildBond</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Main CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body{
            bottom-padding: 500px;
        }
        </style>
</head>
<body>

<div class="container my-4">
  
    <div class="row">
        <?php
        if (!empty($product) && is_array($product)) {
            foreach ($product as $id => $product) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm text-center">
                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 200px; object-fit: contain;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text">
                                MRP: <span class="text-decoration-line-through text-muted">₹<?= $product['mrp'] ?></span>  
                                <span class="text-danger fw-bold">₹<?= $product['price'] ?></span>  
                                <span class="text-success">(<?= $product['discount'] ?>)</span>
                            </p>
                            <button class="btn btn-primary view-btn" data-id="<?= $id ?>">View</button>
                            <a href="cart.php?add=<?= $id ?>" class="btn btn-success">Add to Cart</a>

                            <!-- Hidden Product Details -->
                            <div class="product-details mt-2 p-2 bg-light rounded d-none" id="details-<?= $id ?>">
                                <p><strong>Product Details:</strong></p>
                                <p><?= htmlspecialchars($product['description']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        } else { ?>
            
            
        <?php } ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".view-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            var id = this.getAttribute("data-id");
            var details = document.getElementById("details-" + id);
            if (details) {
                details.classList.toggle("d-none");
            }
        });
    });
});
</script>

<?php include_once(__DIR__ . "/navbar-bottom.php"); ?>  <!-- Footer Navigation -->

</body>
</html>
