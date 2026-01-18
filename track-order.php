<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order | BuildBond</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Main CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    
    <style>
        body {
            bottom-padding: 500px;
        }
    </style>
</head>
<body>

<?php include_once("navbar-top.php"); ?>  <!-- Include Navbar -->

<div class="container my-5">
    <h2 class="text-center mb-4">Track Your Order</h2>
    <p class="text-center">Enter your order ID below to track the status of your order.</p>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form id="trackOrderForm" method="POST">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="order_id" id="order_id" placeholder="Enter Order ID">
                    <button class="btn btn-primary" type="submit">Track</button>
                </div>
                <div id="error-message" class="text-danger text-center"></div> <!-- Validation Message -->
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $order_id = htmlspecialchars($_POST["order_id"]);
                $orders = [
                    "BB12345" => "Shipped - Estimated delivery in 3 days",
                    "BB67890" => "Out for delivery",
                    "BB54321" => "Delivered successfully"
                ];

                if (array_key_exists($order_id, $orders)) {
                    echo "<div class='alert alert-success text-center'>Order Status: " . $orders[$order_id] . "</div>";
                } else {
                    echo "<div class='alert alert-danger text-center'>Invalid Order ID. Please check and try again.</div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include_once("footer.php"); ?>  <!-- Include Footer -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $("#trackOrderForm").submit(function (e) {
        var orderID = $("#order_id").val().trim();
        var regex = /^BB\d{5}$/; // Order ID should start with BB followed by 5 digits

        if (orderID === "") {
            $("#error-message").text("Please enter your Order ID.");
            e.preventDefault();
        } else if (!regex.test(orderID)) {
            $("#error-message").text("Invalid Order ID format. It should start with 'BB' followed by 5 digits (e.g., BB12345).");
            e.preventDefault();
        } else {
            $("#error-message").text(""); // Clear error if valid
        }
    });
});
</script>

</body>
</html>
