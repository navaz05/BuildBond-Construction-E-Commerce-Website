<?php
include 'navbar-top.php';
$error = $_SESSION['otp_error'] ?? '';
unset($_SESSION['otp_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - BuildBond</title>
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
            <h4 class="mb-3 text-center fw-bold" style="color: #05a39c;">✅ Verify OTP</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form action="database/check-otp.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter the OTP sent to your email</label>
                    <input type="text" name="otp" id="otp" placeholder="Enter OTP" required class="form-control">
                </div>
                <button type="submit" class="btn btn-custom w-100">Verify OTP</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
