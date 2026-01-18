<?php
include 'navbar-top.php';
if (!isset($_SESSION['otp_verified'])) {
    header("Location: forgot-password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - BuildBond</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding-bottom: 100px;
        }
        .card {
            background-color: white;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .btn-custom {
            background-color: #05a39c !important;
            color: #fff !important;
            border: none;
        }
        .btn-custom:hover {
            background-color: #04867f !important;
        }
        .form-control:focus {
            border-color: #05a39c;
            box-shadow: 0 0 5px rgba(5, 163, 156, 0.3);
        }
        .error {
            color: #dc3545;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <div class="card p-4">
            <h4 class="mb-3 text-center fw-bold" style="color: #05a39c;">🔐 Reset Password</h4>

            <form id="resetPasswordForm" action="database/update-password.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">
                </div>
                <button type="submit" class="btn btn-custom w-100">Update Password</button>
            </form>
        </div>
    </div>
</div>

<!-- jQuery + Validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function () {
    $("#resetPasswordForm").validate({
        rules: {
            new_password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            new_password: {
                required: "Please enter a new password",
                minlength: "Password must be at least 6 characters"
            }
        },
        errorClass: "error",
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        }
    });
});
</script>

</body>
</html>
