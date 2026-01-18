<?php
session_start();
include '../database/config.php'; // Ensure database connection is included

$message = "";

// ✅ DELETE FEEDBACK
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $query = "DELETE FROM feedback WHERE id = $delete_id";
    if (mysqli_query($con, $query)) {
        $message = "Feedback deleted successfully!";
    } else {
        $message = "Error deleting feedback: " . mysqli_error($con);
    }
}

// ✅ FETCH ALL FEEDBACK
$feedbacks = mysqli_query($con, "SELECT feedback.*, users.name FROM feedback JOIN users ON feedback.user_id = users.id ORDER BY feedback.created_at DESC");

// ✅ FETCH AVERAGE RATING
$rating_result = mysqli_query($con, "SELECT AVG(rating) as avg_rating FROM feedback");
$rating_data = mysqli_fetch_assoc($rating_result);
$average_rating = round($rating_data['avg_rating'], 1);

// ✅ FETCH RATING COUNTS FOR CHART
$rating_counts = [];
for ($i = 1; $i <= 5; $i++) {
    $query = mysqli_query($con, "SELECT COUNT(*) AS count FROM feedback WHERE rating = $i");
    $result = mysqli_fetch_assoc($query);
    $rating_counts[] = $result['count']; // Storing count in array
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'admin_navbar.php'; ?>
<br>
<br>
<div class="container mt-4">
    <h2 class="text-center">User Feedback</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- ✅ Average Rating -->
    <div class="text-center mb-4">
        <h4>⭐ Average Rating: <?php echo $average_rating; ?>/5</h4>
        <canvas id="ratingChart"></canvas>
    </div>

    <!-- ✅ Feedback Table -->
    <div class="card p-4 shadow-sm">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Feedback</th>
                    <th>Rating</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($feedbacks)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['feedback']; ?></td>
                        <td><?php echo str_repeat("⭐", $row['rating']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ JavaScript for Feedback Chart (Now Uses Real Data) -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('ratingChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Ratings',
                data: <?php echo json_encode($rating_counts); ?>, // Fetching real data
                backgroundColor: ['red', 'orange', 'yellow', 'green', 'blue']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
