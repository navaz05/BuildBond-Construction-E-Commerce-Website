<?php include 'db.php'; ?>
<?php include 'navbar-top.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply Coupon | BuildBond</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .coupon-section {
            max-width: 500px;
            margin: 60px auto;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container coupon-section">
    <h2 class="text-center mb-4">🎁 Apply Coupon Code</h2>

    <form method="POST" class="border p-4 shadow rounded bg-light">
        <div class="mb-3">
            <label for="code" class="form-label">Coupon Code</label>
            <input type="text" name="code" id="code" class="form-control" placeholder="Enter your coupon" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Apply Coupon</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $code = strtoupper(trim($_POST['code']));

        $stmt = $conn->prepare("SELECT * FROM coupons WHERE code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<div class="message mt-3">';

        if ($row = $result->fetch_assoc()) {
            $today = date('Y-m-d');

            if ($row['used_count'] >= $row['usage_limit']) {
                echo '<div class="alert alert-danger">❌ Coupon usage limit reached.</div>';
            } elseif ($today > $row['expiry_date']) {
                echo '<div class="alert alert-danger">❌ Coupon expired.</div>';
            } else {
                echo "<div class='alert alert-success'>✅ Coupon applied! You get <strong>{$row['discount_percent']}%</strong> off.</div>";

                // Optional: Uncomment to update used_count
                // $conn->query("UPDATE coupons SET used_count = used_count + 1 WHERE id = {$row['id']}");
            }
        } else {
            echo '<div class="alert alert-danger">❌ Invalid coupon code.</div>';
        }

        echo '</div>';
    }
    ?>

    <div class="text-center mt-4">
        <a href="admin.php" class="btn btn-secondary">⬅ Back to Admin Panel</a>
    </div>
</div>

<!-- Footer (Optional shared footer can be included here) -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <p>&copy; 2025 BuildBond - All Rights Reserved.</p>
</footer>

<!-- JS (Optional, for future enhancements) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
