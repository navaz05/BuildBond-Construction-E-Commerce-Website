<?php

include '../database/config.php'; // Ensure database connection

// Fetch orders
$orders = mysqli_query($con, "SELECT * FROM orders ORDER BY created_at DESC");

// Get total earnings and order count
$total_earnings_query = mysqli_query($con, "SELECT SUM(total_price) AS earnings, COUNT(*) AS total_orders FROM orders WHERE status = 'Delivered'");
$total_data = mysqli_fetch_assoc($total_earnings_query);
$total_earnings = $total_data['earnings'] ?? 0;
$total_orders = $total_data['total_orders'] ?? 0;

// Monthly Earnings
$monthly_earnings_query = mysqli_query($con, "SELECT MONTH(created_at) as month, SUM(total_price) as earnings FROM orders WHERE status = 'Delivered' GROUP BY MONTH(created_at)");
$monthly_earnings = [];
while ($row = mysqli_fetch_assoc($monthly_earnings_query)) {
    $monthly_earnings[$row['month']] = $row['earnings'];
}

// Order Status Update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    mysqli_query($con, "UPDATE orders SET status='$new_status' WHERE id='$order_id'");
    header("Location: admin_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body{
            padding-top: 30px;
        }

    </style>
</head>
<body>


<?php include 'admin_navbar.php'; ?>

<br>
<br>
<div class="container mt-4">
    <h2 class="text-center">Order Management</h2>

    <!-- ✅ Earnings Overview -->
    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Total Earnings</h5>
                <h3>₹<?php echo number_format($total_earnings, 2); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Total Orders</h5>
                <h3><?php echo $total_orders; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Monthly Earnings</h5>
                <canvas id="earningsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ✅ Orders Table -->
    <div class="card p-4 shadow-sm">
        <h4>Orders List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td>₹<?php echo $row['total_price']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo getStatusColor($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <button class="btn btn-warning edit-btn" data-id="<?php echo $row['id']; ?>" data-status="<?php echo $row['status']; ?>" data-bs-toggle="modal" data-bs-target="#editOrderModal">✏️ Update</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Edit Order Status Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="edit_order_id">
                    <label>Status:</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Canceled">Canceled</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_status" class="btn btn-warning">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ jQuery for Edit -->
<script>
$(document).ready(function(){
    $('.edit-btn').click(function(){
        $('#edit_order_id').val($(this).data('id'));
        $('#edit_status').val($(this).data('status'));
    });

    // Earnings Chart
    var ctx = document.getElementById('earningsChart').getContext('2d');
    var earningsData = <?php echo json_encode($monthly_earnings); ?>;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(earningsData),
            datasets: [{
                label: 'Monthly Earnings (₹)',
                data: Object.values(earningsData),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>

<?php
// Function to get status color for Bootstrap badge
function getStatusColor($status) {
    switch ($status) {
        case 'Pending': return 'secondary';
        case 'Processing': return 'primary';
        case 'Shipped': return 'info';
        case 'Delivered': return 'success';
        case 'Canceled': return 'danger';
        default: return 'dark';
    }
}
?>

</body>
</html>
