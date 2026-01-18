<?php include 'navbar-top.php'; ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("Bootstrap JS loaded:", typeof bootstrap !== "undefined");
    });
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error {
            color: #dc3545; /* Bootstrap's red color for errors */
            font-size: 0.875em;
        }
        body{
            padding-bottom: 50px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">We Value Your Feedback</h2>

    <div class="card p-4 shadow-sm">
        <form id="feedbackForm" novalidate>
            <!-- Name -->
            <div class="mb-3">
                <label class="form-label fw-bold">Your Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label fw-bold">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
            </div>

            <!-- Rating -->
            <div class="mb-3">
                <label class="form-label fw-bold">Rate Us</label>
                <select class="form-select" id="rating" name="rating">
                    <option selected disabled value="">Choose a rating</option>
                    <option value="5">⭐️⭐️⭐️⭐️⭐️ - Excellent</option>
                    <option value="4">⭐️⭐️⭐️⭐️ - Very Good</option>
                    <option value="3">⭐️⭐️⭐️ - Good</option>
                    <option value="2">⭐️⭐️ - Fair</option>
                    <option value="1">⭐️ - Poor</option>
                </select>
            </div>

            <!-- Feedback Message -->
            <div class="mb-3">
                <label class="form-label fw-bold">Your Feedback</label>
                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Write your feedback here..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn text-white px-4" style="background-color: #05a39c;">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery Validation Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        $("#feedbackForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                rating: {
                    required: true
                },
                message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    minlength: "Name must be at least 3 characters long"
                },
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                rating: {
                    required: "Please select a rating"
                },
                message: {
                    required: "Please enter your feedback",
                    minlength: "Feedback must be at least 10 characters long"
                }
            },
            errorClass: "text-danger"
        });
    });
</script>
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <h5>We Value Your Feedback!</h5>
        <p>Your opinions help us improve. Thank you for taking the time to share your thoughts.</p>

        <div class="social-icons mt-3">
            <a href="https://www.facebook.com/navaz.odiya.3192" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
            <a href="https://x.com/NavazOdiya?t=hX-wNuCghAgeSHHM7lXBSw&s=09" class="text-white mx-2"><i class="fab fa-x-twitter"></i></a> <!-- Updated to X icon -->
            <a href="https://www.instagram.com/navaz_05_?igsh=b3M0M3JmcGU3eWc4" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/in/navaz-odiya-40427527b?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="text-white mx-2"><i class="fab fa-linkedin"></i></a>
        </div>

        <hr class="my-3 border-light">

        <p class="mb-0">&copy; <?php echo date("Y"); ?> BuildBond. All Rights Reserved.</p>
    </div>
</footer>

<!-- FontAwesome for Icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>


<!-- FontAwesome for Icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

</body>
</html>
