<?php
require 'navbar-top.php';

$error = $_SESSION['forgot_error'] ?? '';
unset($_SESSION['forgot_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - BuildBond</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        label {
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #05a39c;
            box-shadow: 0 0 5px rgba(5, 163, 156, 0.3);
        }
        .text-link {
            color: #05a39c;
            text-decoration: none;
            font-weight: 500;
        }
        .text-link:hover {
            text-decoration: underline;
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
                <h4 class="mb-3 text-center fw-bold" style="color: #05a39c;">🔒 Forgot Password</h4>

                <a href="login.php" class="text-link mb-3 d-block"><i class="bi bi-arrow-left"></i> Go back</a>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form id="forgotForm" action="database/send-otp.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Enter your email address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="you@example.com" required>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Send OTP</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center border-top" style="padding: 20px; position: fixed; bottom: 0; width: 100%;">
        <h5 class="text-dark">🔒 Secure Login</h5>
        <p>Your credentials are encrypted for safety.</p>
        <p><a href="terms-conditions.php" class="text-primary">Terms & Conditions</a> | <a href="privacy-policy.php" class="text-primary">Privacy Policy</a></p>
        <p>&copy; <?= date("Y") ?> BuildBond</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#forgotForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email"
                    }
                },
                errorClass: "error",
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });
        });
    </script>
</body>
</html>