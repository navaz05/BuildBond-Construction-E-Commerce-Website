<?php
include 'navbar-top.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$firstLetter = strtoupper(substr($email, 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .dashboard-container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .user-icon {
            width: 40px;
            height: 40px;
            border-radius: 20%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            border: none;
            text-transform: uppercase;
            background-color: #05a39c !important;
            color: white !important;
            margin: auto;
        }
        .card-title {
            font-weight: bold;
            margin-top: 15px;
        }
        .btn-custom {
            background-color: #05a39c;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #03857f;
        }

        .btn-outline-custom {
            color: #05a39c;
            border: 2px solid #05a39c;
            transition: all 0.3s ease-in-out;
        }
        .btn-outline-custom:hover {
            background-color: #05a39c;
            color: white;
        }

    </style>
</head>
<body>


<div class="dashboard-container mt-4">
    <h2 class="text-center">Welcome, <?php echo $email; ?>!</h2>
    <p class="text-center text-muted">Manage your account easily from here.</p>

    <div class="row g-4 mt-3">

      <!-- Profile Card -->
<div class="col-md-6 col-lg-3">
    <div class="card text-center p-4">
        <div class="user-icon"><?php echo $firstLetter; ?></div>
        <h5 class="card-title mt-3">My Profile</h5>
        <p class="text-muted"><?php echo $email; ?></p>
        <p class="text-muted">Change Your Password.</p>
        <a href="editprofile.php" class="btn btn-outline-custom w-100">
            <i class="bi bi-person-gear"></i> Edit Profile
        </a>
    </div>
</div>


        <!-- Orders -->
        <div class="col-md-6 col-lg-3">
            <div class="card text-center p-4">
                <i class="bi bi-cart-check fs-1 text-success"></i>
                <h5 class="card-title">My Orders</h5>
                <p class="text-muted">Track and manage your orders.</p>
                <a href="myorders.php" class="btn btn-outline-success w-100">View Orders</a>
            </div>
        </div>

        <!-- Feedback -->
        <div class="col-md-6 col-lg-3">
            <div class="card text-center p-4">
                <i class="bi bi-chat-dots fs-1 text-primary"></i>
                <h5 class="card-title">Feedback</h5>
                <p class="text-muted">Share your thoughts with us.</p>
                <a href="feedback.php" class="btn btn-outline-primary w-100">Give Feedback</a>
            </div>
        </div>

        <!-- Logout -->
        <div class="col-md-6 col-lg-3">
            <div class="card text-center p-4">
                <i class="bi bi-box-arrow-right fs-1 text-danger"></i>
                <h5 class="card-title">Logout</h5>
                <p class="text-muted">Sign out of your account.</p>
                <a href="database/logout.php" class="btn btn-outline-danger w-100">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
