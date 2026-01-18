<?php
session_start();
include '../database/config.php'; // database connection

$message = "";

// ✅ ADD COUPON
if (isset($_POST['add_coupon'])) {
    $code = strtoupper(trim(mysqli_real_escape_string($con, $_POST['code'])));
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $expiry_date = $_POST['expiry_date'];

    $query = "INSERT INTO coupons (code, description, discount, expiry_date)
              VALUES ('$code', '$description', '$discount', '$expiry_date')";
    $message = mysqli_query($con, $query) ? "Coupon added successfully!" : "Error: " . mysqli_error($con);
}

// ✅ UPDATE COUPON
if (isset($_POST['update_coupon'])) {
    $id = $_POST['edit_id'];
    $code = strtoupper(trim(mysqli_real_escape_string($con, $_POST['code'])));
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $expiry_date = $_POST['expiry_date'];

    $query = "UPDATE coupons SET code='$code', description='$description', discount='$discount', expiry_date='$expiry_date' WHERE id='$id'";
    $message = mysqli_query($con, $query) ? "Coupon updated successfully!" : "Error updating coupon: " . mysqli_error($con);
}

// ✅ DELETE COUPON
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM coupons WHERE id = $delete_id");
}

// ✅ FETCH COUPONS
$coupons = mysqli_query($con, "SELECT * FROM coupons ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Coupons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Manage Coupons</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCouponModal">➕ Add Coupon</button>

    <div class="card p-4 shadow-sm">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Discount (%)</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($coupons)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['code']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['discount']; ?>%</td>
                        <td><?php echo $row['expiry_date']; ?></td>
                        <td>
                            <button class="btn btn-warning edit-btn"
                                data-id="<?php echo $row['id']; ?>"
                                data-code="<?php echo $row['code']; ?>"
                                data-description="<?php echo $row['description']; ?>"
                                data-discount="<?php echo $row['discount']; ?>"
                                data-expiry="<?php echo $row['expiry_date']; ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#editCouponModal">✏️ Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ ADD COUPON MODAL -->
<div class="modal fade" id="addCouponModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCouponForm" method="POST">
                <div class="modal-body">
                    <input type="text" name="code" class="form-control mb-2" placeholder="Coupon Code (e.g. SAVE20)">
                    <textarea name="description" class="form-control mb-2" placeholder="Coupon Description"></textarea>
                    <input type="number" name="discount" class="form-control mb-2" placeholder="Discount %" step="0.01">
                    <input type="date" name="expiry_date" class="form-control mb-2">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_coupon" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ EDIT COUPON MODAL -->
<div class="modal fade" id="editCouponModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCouponForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <input type="text" name="code" id="edit_code" class="form-control mb-2" placeholder="Coupon Code">
                    <textarea name="description" id="edit_description" class="form-control mb-2" placeholder="Coupon Description"></textarea>
                    <input type="number" name="discount" id="edit_discount" class="form-control mb-2" placeholder="Discount %" step="0.01">
                    <input type="date" name="expiry_date" id="edit_expiry" class="form-control mb-2">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_coupon" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ jQuery Validation & Modal Fill Script -->
<script>
$(document).ready(function () {
    // ✅ Add Coupon Form Validation
    $("#addCouponForm").validate({
        rules: {
            code: { required: true, minlength: 4 },
            description: { required: true, minlength: 5 },
            discount: { required: true, number: true, min: 1, max: 100 },
            expiry_date: { required: true, date: true }
        },
        messages: {
            code: "Enter a valid code (min 4 chars)",
            description: "Description must be at least 5 characters",
            discount: "Enter a valid discount (1-100%)",
            expiry_date: "Please select an expiry date"
        },
        errorClass: "text-danger",
        submitHandler: function(form) {
            form.submit();
        }
    });

    // ✅ Fill Edit Modal with Data
    $('.edit-btn').click(function () {
        $('#edit_id').val($(this).data('id'));
        $('#edit_code').val($(this).data('code'));
        $('#edit_description').val($(this).data('description'));
        $('#edit_discount').val($(this).data('discount'));
        $('#edit_expiry').val($(this).data('expiry'));
    });

    // ✅ Edit Coupon Form Validation
    $("#editCouponForm").validate({
        rules: {
            code: { required: true, minlength: 4 },
            description: { required: true, minlength: 5 },
            discount: { required: true, number: true, min: 1, max: 100 },
            expiry_date: { required: true, date: true }
        },
        errorClass: "text-danger",
        submitHandler: function(form) {
            form.submit();
        }
    });
});
</script>

</body>
</html>
