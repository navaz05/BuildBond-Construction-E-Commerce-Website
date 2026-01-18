<?php
include '../database/config.php'; // Database connection
$message = "";

// ✅ ADD PRODUCT
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $mrp = (float) $_POST['mrp'];
    $selling_price = (float) $_POST['selling_price'];
    $stock = (int) $_POST['stock'];
    $category = mysqli_real_escape_string($con, $_POST['category']);

    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
    }

    $query = "INSERT INTO products (name, description, selling_price, mrp, stock, category, image)
              VALUES ('$name', '$description', '$selling_price', '$mrp', '$stock', '$category', '$imageName')";

    if (mysqli_query($con, $query)) {
        $message = "✅ Product added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($con);
    }
}

// ✅ DELETE PRODUCT
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM products WHERE id = $delete_id");
    header("Location: admin_products.php");
    exit;
}

// ✅ EDIT PRODUCT
if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $mrp = (float) $_POST['mrp'];
    $selling_price = (float) $_POST['selling_price'];
    $stock = (int) $_POST['stock'];
    $category = mysqli_real_escape_string($con, $_POST['category']);

    $imageUpdate = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
        $imageUpdate = ", image = '$imageName'";
    }

    $update = "UPDATE products SET 
               name = '$name', 
               description = '$description', 
               mrp = '$mrp', 
               selling_price = '$selling_price', 
               stock = '$stock', 
               category = '$category'
               $imageUpdate
               WHERE id = $id";

    if (mysqli_query($con, $update)) {
        $message = "✅ Product updated successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($con);
    }
}

// ✅ FETCH PRODUCTS
$products = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        small.text-danger {
    display: block;
    margin-top: 2px;
    font-size: 0.875rem;
}
</style>
</head>
<body>
<?php include 'admin_navbar.php'; ?>

<div class="container mt-4 pt-5">
    <h2 class="text-center fw-bold pt-4">📦 Manage Products</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">➕ Add New Product</button>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>MRP</th>
                <th>Selling Price</th>
                <th>Discount</th>
                <th>Stock</th>
                <th>Sold</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $editModals = "";
            while ($row = mysqli_fetch_assoc($products)) { 
            ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><img src="../uploads/<?= $row['image'] ?>" width="60"></td>
                    <td><?= $row['name'] ?></td>
                    <td>₹<?= $row['mrp'] ?></td>
                    <td>₹<?= $row['selling_price'] ?></td>
                    <td class="text-success fw-bold"><?= $row['discount_percent'] ?>%</td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= $row['sold'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">🗑️</a>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $row['id'] ?>">✏️</button>
                    </td>
                </tr>
            <?php
                // Store the modal HTML
                $editModals .= '
                <div class="modal fade" id="editProductModal' . $row['id'] . '" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content edit-form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="' . $row['id'] . '">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control mb-2" value="' . htmlspecialchars($row['name']) . '" >
                                <textarea name="description" class="form-control mb-2" >' . htmlspecialchars($row['description']) . '</textarea>
                                <input type="number" step="0.01" name="mrp" class="form-control mb-2" value="' . $row['mrp'] . '" >
                                <input type="number" step="0.01" name="selling_price" class="form-control mb-2" value="' . $row['selling_price'] . '" >
                                <input type="number" name="stock" class="form-control mb-2" value="' . $row['stock'] . '" >
                                <input type="text" name="category" class="form-control mb-2" value="' . htmlspecialchars($row['category']) . '" >
                                <input type="file" name="image" class="form-control mb-2" accept="image/*">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit_product" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>';
            } ?>
        </tbody>
    </table>

    <!-- ✅ Render all edit modals here -->
    <?= $editModals ?>

</div>

<!-- ✅ ADD PRODUCT MODAL -->
<div class="modal fade" id="addProductModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content product-form" method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="name" class="form-control mb-2" placeholder="Product Name" >
        <textarea name="description" class="form-control mb-2" placeholder="Product Description" ></textarea>
        <input type="number" step="0.01" name="mrp" class="form-control mb-2" placeholder="MRP ₹" >
        <input type="number" step="0.01" name="selling_price" class="form-control mb-2" placeholder="Selling Price ₹" >
        <input type="number" name="stock" class="form-control mb-2" placeholder="Stock Quantity" >
        <input type="text" name="category" class="form-control mb-2" placeholder="Category" >
        <input type="file" name="image" class="form-control mb-2" accept="image/*" >
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ jQuery Validation -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


<script>
$(document).ready(function () {
    function setupValidation(form) {
        $(form).validate({
            rules: {
                name: { required: true },
                description: { required: true },
                mrp: {
                    required: true,
                    number: true,
                    min: 1
                },
                selling_price: {
                    required: true,
                    number: true,
                    min: 1,
                    max: function () {
                        const mrpVal = parseFloat($(form).find("input[name='mrp']").val()) || 0;
                        return mrpVal;
                    }
                },
                stock: {
                    required: true,
                    digits: true,
                    min: 0
                },
                category: { required: true },
                image: {
                    required: $(form).hasClass("product-form"), // image is required only in add form
                    extension: "jpg|jpeg|png|webp"
                }
            },
            messages: {
                name: "Product name is required.",
                description: "Description cannot be empty.",
                mrp: {
                    required: "MRP is required.",
                    number: "Enter a valid number.",
                    min: "MRP must be greater than 0."
                },
                selling_price: {
                    required: "Selling price is required.",
                    number: "Enter a valid number.",
                    min: "Selling price must be greater than 0.",
                    max: "Selling price must not exceed MRP."
                },
                stock: {
                    required: "Stock quantity is required.",
                    digits: "Only whole numbers allowed.",
                    min: "Stock cannot be negative."
                },
                category: "Product category is required.",
                image: {
                    required: "Please upload an image.",
                    extension: "Only JPG, JPEG, PNG, or WEBP allowed."
                }
            },
            errorClass: "text-danger",
            errorElement: "small",
            highlight: function (element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid");
            },
            submitHandler: function (form) {
                form.submit(); // Only submits if valid
            }
        });
    }

    // ✅ Add Product Form
    setupValidation(".product-form");

    // ✅ Edit Product Forms — validate all at once
    $("form.edit-form").each(function () {
        setupValidation(this);
    });

    // ✅ Just in case: Prevent accidental invalid submissions
    $(document).on("submit", "form.edit-form", function (e) {
        const $form = $(this);
        if (!$form.valid()) {
            e.preventDefault();
        }
    });
});

</script>




</body>
</html>
