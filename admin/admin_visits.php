<?php
session_start();
include '../database/config.php'; // Database connection

// ✅ Track User Visits
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
$ip_address = $_SERVER['REMOTE_ADDR']; 

mysqli_query($con, "INSERT INTO visits (ip_address, user_id) VALUES ('$ip_address', NULLIF('$user_id', ''))");

// ✅ Fetch Total Visits
$total_visits_query = mysqli_query($con, "SELECT COUNT(*) AS total FROM visits");
$total_visits = mysqli_fetch_assoc($total_visits_query)['total'];

// ✅ Fetch Registered User Visits
$registered_visits_query = mysqli_query($con, "SELECT COUNT(*) AS total FROM visits WHERE user_id IS NOT NULL");
$registered_visits = mysqli_fetch_assoc($registered_visits_query)['total'];

// ✅ Fetch Guest Visits
$guest_visits = $total_visits - $registered_visits;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Visits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Website Visits Overview</h2>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h4>🌐 Total Visits</h4>
                <h2 id="totalVisits"><?php echo $total_visits; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h4>👤 Registered User Visits</h4>
                <h2 id="registeredVisits"><?php echo $registered_visits; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h4>🕵️‍♂️ Guest Visits</h4>
                <h2 id="guestVisits"><?php echo $guest_visits; ?></h2>
            </div>
        </div>
    </div>

</div>

<script>
$(document).ready(function () {
    function loadVisitStats() {
        $.ajax({
            url: "admin_visits.php",
            method: "GET",
            success: function (data) {
                $("#totalVisits").text($(data).find("#totalVisits").text());
                $("#registeredVisits").text($(data).find("#registeredVisits").text());
                $("#guestVisits").text($(data).find("#guestVisits").text());
            }
        });
    }
    setInterval(loadVisitStats, 5000);
});
</script>

</body>
</html>
