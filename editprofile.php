<?php 

include 'navbar-top.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .btn-custom {
            background-color: #05a39c;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #03857f;
        }
        .form-control:focus {
            border-color: #05a39c;
            box-shadow: 0 0 5px rgba(5, 163, 156, 0.5);
        }
        .error {
            color: #dc3545;
            font-size: 0.875em;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <h4 class="fw-bold text-center">Change Password</h4>

                <!-- Session Messages -->
                <?php if(isset($_SESSION['edit_error']) && !empty($_SESSION['edit_error'])): ?>
                    <p class="text-danger text-center"><?= $_SESSION['edit_error']; ?></p>
                    <?php unset($_SESSION['edit_error']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['edit_success']) && !empty($_SESSION['edit_success'])): ?>
                    <p class="text-success text-center"><?= $_SESSION['edit_success']; ?></p>
                    <?php unset($_SESSION['edit_success']); ?>
                <?php endif; ?>

                <form method="post" id="changePasswordForm" action="editprocess.php">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Old Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter old password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                    </div>
                    <button type="submit" class="btn btn-custom w-100" name="change_password">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & Validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $("#changePasswordForm").validate({
            rules: {
                old_password: {
                    required: true
                },
                new_password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
                old_password: {
                    required: "Please enter your old password"
                },
                new_password: {
                    required: "Please enter a new password",
                    minlength: "Password must be at least 6 characters long"
                },
                confirm_password: {
                    required: "Please confirm your new password",
                    equalTo: "Passwords do not match"
                }
            },
            errorClass: "text-danger"
        });
    });
</script>

</body>
</html>
