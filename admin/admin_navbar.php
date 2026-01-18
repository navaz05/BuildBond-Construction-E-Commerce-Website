<?php 
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['email']);
$email = $isLoggedIn ? $_SESSION['email'] : "";
$firstLetter = $isLoggedIn ? strtoupper(substr($email, 0, 1)) : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BuildBond</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Fixed Admin Navbar with Bounce Effect */
        .admin-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: white;
            z-index: 1050;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-bottom: 4px solid #05a39c; /* Match old top-navbar */
            transition: transform 0.3s ease-in-out, top 0.3s ease-in-out;
            animation: bounceIn 0.4s ease-in-out;
        }



        /* Navbar Links */
        .navbar-nav .nav-link {
            color: #333;
            font-weight: 600;
            transition: color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover {
            color: #05a39c;
            transform: scale(1.1);
        }

        /* Admin User Icon */
        .user-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            background-color: #05a39c !important;
            color: white !important;
            border: none;
            text-transform: uppercase;
        }

        /* Dropdown Alignment */
        .dropdown-menu {
            left: auto !important;
            right: 0 !important;
        }

        /* Page Content */
        .content {
            margin-top: 80px; /* Ensure content doesn't hide behind fixed navbar */
        }
    </style>
</head>
<body>

<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white admin-navbar">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="admin_dashboard.php">
            <img src="../assets/logo.png" alt="Logo" height="50">
        </a>

        <!-- Navbar Toggle for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse justify-content-between" id="adminNavbar">
            <!-- Links -->
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_users.php"><i class="bi bi-people"></i> Users</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_orders.php"><i class="bi bi-bag"></i> Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_products.php"><i class="bi bi-box"></i> Products</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_feedback.php"><i class="bi bi-chat-left-text"></i> Feedback</a></li>


                
            </ul>

            <!-- Right Side: User Dropdown -->
            <div class="d-flex align-items-center">
                <!-- Admin User Dropdown -->
                <div class="dropdown">
                    <button class="btn user-icon" id="adminDropdown">
                        <?php echo $isLoggedIn ? $firstLetter : '<i class="bi bi-person"></i>'; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" id="adminDropdownMenu">
                        <?php if ($isLoggedIn): ?>
                            <!-- <li><a class="dropdown-item" href="admin_profile.php"><i class="bi bi-person"></i> Profile</a></li> -->
                            <li><a class="dropdown-item btn btn-danger text-danger" href="../database/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Toggle dropdown on button click
        $("#adminDropdown").click(function (event) {
            $("#adminDropdownMenu").toggleClass("show");
            event.stopPropagation();
        });

        // Close dropdown when clicking outside
        $(document).click(function () {
            $("#adminDropdownMenu").removeClass("show");
        });

        // Sticky Navbar with Animation
        let adminNavbar = $(".admin-navbar");
        let lastScrollTop = 0;

        $(window).on("scroll", function () {
            let scrollTop = $(this).scrollTop();

            if (scrollTop > 50) {
                adminNavbar.addClass("fixed"); // Make navbar fixed
            } else {
                adminNavbar.removeClass("fixed");
            }

            lastScrollTop = scrollTop;
        });
    });
</script>

</body>
</html>
