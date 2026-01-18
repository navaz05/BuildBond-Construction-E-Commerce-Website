<?php include '../database/config.php'; // Database connection

// Fetch total users
$totalUsersQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM users");
$totalUsers = mysqli_fetch_assoc($totalUsersQuery)['total'];

// Fetch total products
$totalProductsQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM products");
$totalProducts = mysqli_fetch_assoc($totalProductsQuery)['total'];

// Fetch total orders
$totalOrdersQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM orders");
$totalOrders = mysqli_fetch_assoc($totalOrdersQuery)['total'];

// Fetch total earnings
$totalEarningsQuery = mysqli_query($con, "SELECT SUM(total_price) AS total FROM orders WHERE status = 'Delivered'");
$totalEarnings = mysqli_fetch_assoc($totalEarningsQuery)['total'] ?? 0;

// Fetch recent orders
$recentOrdersQuery = mysqli_query($con, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");

// Fetch site visits
$totalVisitsQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM visits");
$totalVisits = mysqli_fetch_assoc($totalVisitsQuery)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
                        color: white;
                        padding-top: 50px;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            border-radius: 12px;
            transition: all 0.3s ease-in-out;
            background-color: #fff;
            color: black;
            border: none;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }
        .card i {
            font-size: 2.5rem;
        }
        .table {
            background: white;
            border-radius: 10px;
        }
        .table th, .table td {
            padding: 12px;
        }
        .quick-links a {
            text-decoration: none;
            color: #05a39c;
            font-weight: bold;
        }
        .quick-links a:hover {
            color: #037971;
        }
        .quick-links .list-group-item {
            transition: background 0.2s ease-in-out;
        }
        .quick-links .list-group-item:hover {
            background: rgba(5, 163, 156, 0.1);
        }
    </style>
</head>
<body>

<?php include 'admin_navbar.php'; ?> <!-- Include admin navbar -->
<br>
<br>
<div class="container">
    <h2 class="text-center mb-4">Admin Dashboard</h2>

    <div class="row g-4">
        <!-- Overview Cards -->
        <div class="col-md-3">
            <div class="card text-center p-4">
                <i class="bi bi-people text-primary"></i>
                <h5 class="mt-3">Total Users</h5>
                <h3><?php echo $totalUsers; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-4">
                <i class="bi bi-box text-warning"></i>
                <h5 class="mt-3">Total Products</h5>
                <h3><?php echo $totalProducts; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-4">
                <i class="bi bi-cart-check text-success"></i>
                <h5 class="mt-3">Total Orders</h5>
                <h3><?php echo $totalOrders; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-4">
                <i class="bi bi-currency-dollar text-danger"></i>
                <h5 class="mt-3">Total Earnings</h5>
                <h3>₹<?php echo number_format($totalEarnings, 2); ?></h3>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Site Visits -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card p-3">
                <h5><i class="bi bi-clock-history"></i> Recent Orders</h5>
                <table class="table table-hover mt-2">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($recentOrdersQuery)) { ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['user_id']; ?></td>
                                <td><span class="badge bg-info"><?php echo $order['status']; ?></span></td>
                                <td><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Site Visits -->
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h5><i class="bi bi-eye"></i> Site Visits</h5>
                <h1 class="display-4"><?php echo $totalVisits; ?></h1>
                <p class="text-muted">Total visits</p>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card p-3">
                <h5><i class="bi bi-link-45deg"></i> Quick Links</h5>
                <ul class="list-group quick-links mt-2">
                    <li class="list-group-item"><a href="admin_users.php"><i class="bi bi-person"></i> Manage Users</a></li>
                    <li class="list-group-item"><a href="admin_products.php"><i class="bi bi-box"></i> Manage Products</a></li>
                    <li class="list-group-item"><a href="admin_orders.php"><i class="bi bi-cart"></i> Manage Orders</a></li>
                    <li class="list-group-item"><a href="admin_earnings.php"><i class="bi bi-currency-dollar"></i> Earnings</a></li>
                    <li class="list-group-item"><a href="admin_offers.php"><i class="bi bi-tag"></i> Manage Offers</a></li>
                    <li class="list-group-item"><a href="admin_visits.php"><i class="bi bi-eye"></i> View Visits</a></li>
                </ul>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
