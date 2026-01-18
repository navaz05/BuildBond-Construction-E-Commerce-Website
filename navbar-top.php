<?php 
session_start();
include 'scrol.php'; 

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
    <title>BuildBond </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* Top Navbar */
        .top-navbar {
            position: relative;
            width: 100%;
            background: white;
            z-index: 1050;
            transition: transform 0.3s ease-in-out, top 0.3s ease-in-out;
        }

        /* Fixed Navbar with Bounce Effect */
        .top-navbar.fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            animation: bounceIn 0.4s ease-in-out;
        }

        /* Cool Bounce Animation */
        @keyframes bounceIn {
            0% { transform: translateY(-100%); }
            50% { transform: translateY(5px); }
            100% { transform: translateY(0); }
        }

        /* User Icon Styling */
        .user-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            border: none;
            text-transform: uppercase;
            background-color: #05a39c !important;
            color: white !important;
        }

        .dropdown-menu {
            left: 0 !important; /* Align to left */
            right: auto !important;
            transform: translateX(-60%);
        }
        .d-flex{
            display: flex;
            justify-content: space-between;
            font-size: 20px;
            font-weight: bold;
            gap: 1rem;
            font-family: 'Poppins', sans-serif;
            margin-left: 1px;
        }
        
        .user-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;  /* Space between icon and text */
    padding: 6px 12px;
    background-color: #05a39c !important;
    color: white !important;
    border-radius: 20px; /* Rounded button */
    font-size: 16px;
    text-transform: uppercase;
    width: auto; /* Auto width for button */
}

.user-icon .login-text {
    display: inline-block;
    color: white;
    font-size: 16px;
    font-weight: bold;
}

.dropdown-menu {
    min-width: 160px;
    left: auto !important;
    right: 0 !important;
    transform: none !important; /* Remove unwanted shift */
}


.dropdown:hover .dropdown-menu {
    display: block;
}

/* Search Bar Styling */
.search-container {
    position: relative;
    width: 250px;
    margin-right: 15px;
}

.search-container .input-group {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.search-container input {
    border: none;
    padding: 8px 15px;
    border-radius: 20px 0 0 20px;
}

.search-container button {
    border-radius: 0 20px 20px 0;
    padding: 8px 15px;
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 0 0 10px 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

.suggestion-item {
    padding: 8px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.suggestion-item:hover {
    background-color: #f8f8f8;
}

.suggestion-item:last-child {
    border-bottom: none;
}

/* Responsive Fix */
@media (max-width: 768px) {
    .user-icon {
        font-size: 14px;
        padding: 5px 8px;
    }

    .user-icon .login-text {
        font-size: 14px;
    }
    
    .search-container {
        width: 200px;
        margin-right: 10px;
    }
}

    </style>
</head>
<body>


<!-- Top Navbar -->
<nav class="navbar navbar-light bg-white border-bottom border-4 px-3 top-navbar" style="border-color: #05a39c !important;">
    <div class="container-fluid d-flex flex-wrap justify-content-center justify-content-md-between align-items-center">
        
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="assets/logo.png" alt="Logo" height="50">
        </a>

        <!-- Navigation Links -->
        <div class="d-flex flex-wrap justify-content-center justify-content-md-start">
            <a href="index.php" class="me-3 text-dark text-decoration-none">Home</a>
            <?php if ($isLoggedIn): ?>
                <a href="dash.php" class="me-3 text-dark text-decoration-none">Dashboard</a>
            <?php endif; ?>
            <a href="about.php" class="me-3 text-dark text-decoration-none">About</a>
            <a href="contact.php" class="me-3 text-dark text-decoration-none">Contact Us</a>
            <a href="product.php" class="me-3 text-dark text-decoration-none">Products</a>
            <a href="design.php" class="me-3 text-dark text-decoration-none">Design</a>

            <!-- Cart Icon -->
            <a href="cart.php" class="text-dark me-3 position-relative  text-decoration-none">
            Cart <i class="bi bi-cart fs-5"></i>
            </a>
        </div>



        <!-- Right Side: Contact & User Icon -->
        <div class="d-flex align-items-center">
            <!-- Search Field with Suggestions -->
            <div class="search-container me-3 position-relative">
                <form action="product.php" method="GET" class="d-flex m-0" id="searchForm">
                    <div class="input-group">
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search products..." aria-label="Search" autocomplete="off">
                        <button class="btn" type="submit" style="background-color: #05a39c; color: white;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <!-- Hidden input for category -->
                    <input type="hidden" name="category" id="searchCategory" value="">
                </form>
                <div id="searchSuggestions" class="search-suggestions d-none">
                    <!-- Suggestions will be populated here via JavaScript -->
                </div>
            </div>

            <div class="dropdown">
    <?php if ($isLoggedIn): ?>
        <!-- Logged In: Show User Initial with Dropdown -->
        <button class="btn user-icon d-flex align-items-center" id="userDropdown">
            <span class="user-initial"><?php echo $firstLetter; ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" id="dropdownMenu">
            <li><a class="dropdown-item" href="editprofile.php"><i class="bi bi-person"></i> Edit Profile</a></li>
            <li><a class="dropdown-item" href="database/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    <?php else: ?>
        <!-- Not Logged In: Show "Login" button (redirects to login page) -->
        <a href="login.php" class="btn user-icon d-flex align-items-center text-decoration-none">
            <i class="bi bi-person"></i>
            <span class="ms-2 login-text">Login</span>
        </a>
    <?php endif; ?>
</div>




        </div>
    </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Search Suggestions Script -->
<script>
    $(document).ready(function() {
        const searchInput = $('#searchInput');
        const suggestionsContainer = $('#searchSuggestions');
        const searchForm = $('#searchForm');
        const searchCategoryInput = $('#searchCategory');
        let searchTimeout;
        let lastQuery = '';
        
        // Determine current page category
        let currentCategory = '';
        const currentPath = window.location.pathname;
        if (currentPath.includes('product.php')) {
            currentCategory = 'product';
            searchForm.attr('action', 'product.php');
        } else if (currentPath.includes('design.php')) {
            currentCategory = 'design';
            searchForm.attr('action', 'design.php');
        }
        
        // Set the category in the hidden input
        searchCategoryInput.val(currentCategory);
        
        // Function to fetch suggestions
        function fetchSuggestions(query) {
            if (query.length < 1) {
                suggestionsContainer.addClass('d-none');
                return;
            }
            
            // Don't make a new request if the query hasn't changed
            if (query === lastQuery) {
                return;
            }
            
            lastQuery = query;
            
            $.ajax({
                url: 'get_suggestions.php',
                method: 'GET',
                data: { 
                    query: query,
                    category: currentCategory
                },
                success: function(data) {
                    if (data.suggestions && data.suggestions.length > 0) {
                        let html = '';
                        data.suggestions.forEach(function(item) {
                            // Add category indicator if not on the same page
                            let categoryLabel = '';
                            if (currentCategory && item.category !== currentCategory) {
                                categoryLabel = `<span class="badge bg-secondary ms-2">${item.category}</span>`;
                            }
                            
                            html += `<div class="suggestion-item" data-category="${item.category}">
                                ${item.name}${categoryLabel}
                                <div class="small text-muted">${item.description}</div>
                            </div>`;
                        });
                        suggestionsContainer.html(html).removeClass('d-none');
                    } else {
                        suggestionsContainer.addClass('d-none');
                    }
                },
                error: function() {
                    suggestionsContainer.addClass('d-none');
                }
            });
        }
        
        // Handle input changes - character by character
        searchInput.on('input', function() {
            const query = $(this).val().trim();
            
            // Clear any existing timeout
            clearTimeout(searchTimeout);
            
            // Set a very short timeout to allow for rapid typing
            searchTimeout = setTimeout(function() {
                fetchSuggestions(query);
            }, 100); // Reduced from 300ms to 100ms for faster response
        });
        
        // Handle suggestion click
        $(document).on('click', '.suggestion-item', function() {
            const category = $(this).data('category');
            const searchQuery = searchInput.val().trim();
            
            // Navigate to the appropriate page based on category
            if (category === 'design') {
                window.location.href = `design.php?search=${encodeURIComponent(searchQuery)}`;
            } else {
                window.location.href = `product.php?search=${encodeURIComponent(searchQuery)}`;
            }
        });
        
        // Handle form submission
        searchForm.on('submit', function(e) {
            const query = searchInput.val().trim();
            if (query.length < 1) {
                e.preventDefault();
                alert('Please enter at least 1 character to search');
                return false;
            }
        });
        
        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-container').length) {
                suggestionsContainer.addClass('d-none');
            }
        });
        
        // Focus on search input when pressing '/' key
        $(document).on('keydown', function(e) {
            // Only trigger if not already in an input field
            if (e.key === '/' && !$(e.target).is('input, textarea')) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    });
</script>

<!-- Auto-hover dropdown script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let dropdown = document.querySelector(".dropdown");
    let menu = document.getElementById("dropdownMenu");

    dropdown.addEventListener("mouseenter", function () {
        menu.classList.add("show");
    });

    dropdown.addEventListener("mouseleave", function () {
        menu.classList.remove("show");
    });
});
</script>
<script>
    $(document).ready(function () {
        // Toggle dropdown on button click
        $("#userDropdown").click(function (event) {
            $("#dropdownMenu").toggleClass("show");
            event.stopPropagation(); // Prevent closing when clicking inside dropdown
        });

        // Close dropdown when clicking outside
        $(document).click(function () {
            $("#dropdownMenu").removeClass("show");
        });

        // Sticky navbar effect
        let topNavbar = $(".top-navbar");
        let topNavbarHeight = topNavbar.outerHeight();
        let lastScrollTop = 0;

        $(window).on("scroll", function () {
            let scrollTop = $(this).scrollTop();

            if (scrollTop > topNavbarHeight) {
                topNavbar.addClass("fixed"); // Make it fixed when scrolled
            } else {
                topNavbar.removeClass("fixed"); // Normal when at the top
            }

            lastScrollTop = scrollTop;
        });
    });
</script>

</body>
</html>
