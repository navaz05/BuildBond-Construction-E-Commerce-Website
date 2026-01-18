<?php include 'navbar-top.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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
            margin-top: 5px;
        }
    </style>
</head>
<body>
<?php
$email_attempt = isset($_SESSION['email_attempt']) ? $_SESSION['email_attempt'] : '';
$error_type = isset($_SESSION['signin_error_type']) ? $_SESSION['signin_error_type'] : '';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="row">
                <!-- Login Section -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <h4 class="fw-bold">Returning Customer</h4>
                        <form id="loginForm" method="post" action="database/loginprocess.php" novalidate>
                            <!-- Email input -->
<div class="mb-3">
    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email_attempt) ?>">

    <?php if ($error_type === 'email_not_found'): ?>
        <div class="error"><i class="bi bi-exclamation-octagon"></i> We cannot find an account with that email address.</div>
    <?php elseif ($error_type === 'invalid_password'): ?>
        <div class="error">Wrong PASSWORD. Please correct and try again.</div>
    <?php endif; ?>
</div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>
                            
                            <p class="text-muted small">This site is protected by reCAPTCHA and the Google <a href="privacy-policy.php">Privacy Policy</a> and <a href="terms-of-service.php">Terms of Service</a> apply.</p>
                            <button type="submit" class="btn btn-custom w-100" name="login">Log In</button>
                            <div class="mt-2">
                                <a href="forgot-password.php" class="text-decoration-none text-primary">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
unset($_SESSION['signin_error_type']);
unset($_SESSION['email_attempt']);
?>

                <!-- Create Account Section -->
                <div class="col-md-6">
                    <div class="card">
                        <h4 class="fw-bold">Create an Account</h4>
                        <ul class="list-unstyled">
                            <li>✔ Easily track and manage your orders</li>
                            <li>✔ Save all of your order history and set up returns</li>
                            <li>✔ Quickly checkout</li>
                            <li>✔ Save, organize, and share your favorite products</li>
                            <li>✔ Review the products you purchased</li>
                        </ul>
                        <a href="register.php" class="btn btn-custom w-100">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & Validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: {
                required: "Wrong or Invalid email address. Please correct and try again.",
                email: "Wrong or Invalid email address. Please correct and try again."
            },
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 6 characters long"
            }
        },
        errorClass: "text-danger",
        errorPlacement: function(error, element) {
            // Place error messages just after the form control
            error.insertAfter(element);
        }
    });
});
</script>

<footer class="bg-light text-center py-4 border-top">
    <h5 class="text-dark">🔒 Secure Login</h5>
    <p>Your credentials are encrypted for safety.</p>
    <p><a href="terms-conditions.php" class="text-primary">Terms & Conditions</a> | <a href="privacy-policy.php" class="text-primary">Privacy Policy</a></p>
    <p>&copy; <?php echo date("Y"); ?> BuildBond</p>
</footer>

</body>
</html>
