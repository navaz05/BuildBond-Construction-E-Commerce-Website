<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require '../database/config.php'; // Database connection

// Initialize variables
$totalEarnings = 0.00;
$monthlyEarnings = [];

// Fetch total earnings
$query = "SELECT SUM(total_price) AS total FROM orders WHERE status = 'Delivered'";
$result = $con->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $totalEarnings = $row['total'] ?? 0.00;
}

// Fetch monthly earnings
$query = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_price) AS total
          FROM orders
          WHERE status = 'Delivered'
          GROUP BY DATE_FORMAT(created_at, '%Y-%m')";
$result = $con->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $monthlyEarnings[$row['month']] = $row['total'];
    }
}
?>
 <?php include 'admin_navbar.php'; ?>
 <br>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Earnings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Earnings Overview</h1>
        <div class="card mb-4">
            <div class="card-body">
                <h3>Total Earnings: ₹<?php echo number_format($totalEarnings, 2); ?></h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <canvas id="earningsChart"></canvas>
            </div>
        </div>
    </div>
   <br>
    <script>
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const earningsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_keys($monthlyEarnings)); ?>,
                datasets: [{
                    label: 'Monthly Earnings (₹)',
                    data: <?php echo json_encode(array_values($monthlyEarnings)); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Earnings Trend'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Earnings (₹)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
