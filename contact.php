<?php include 'navbar-top.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - My Simple Blog</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .error {
            color: #dc3545; /* Bootstrap's red color for errors */
        }
    </style>
</head>
<body>


<div class="container mt-5">
    <h1>Contact Us</h1>
    <p>If you have any questions or feedback, feel free to reach out!</p>
    <form id="contactForm" novalidate>
        <div class="mb-3">
            <label for="ame" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="4" ></textarea>
        </div>
        <div id="statusMessage" class="text-primary mb-3" style="display: none;"></div>
        <button type="submit" class="btn btn-primary" id="submit" style="background-color: #05a39c;">Send Message</button>
    </form>
</div>



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery Validation Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom Script -->
<script>
    $(document).ready(function(){
        $("#contactForm").validate({
            rules:{
                name:{
                    required: true,
                    minlength: 3
                },
                email:{
                    required: true,
                    email: true
                },
                message:{
                    required: true
                }
            },
            messages:{
                name:{
                    required: "Please enter your name",
                    minlength: "Name must be at least 3 characters long"
                },
                email:{
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                message:{
                    required: "Please enter a message"
                },
                errorClass: "text-danger",
                submitHandler:function(form){
                    form.submit();
            }
            }
        });
    });
</script>
<footer class="bg-dark text-white text-center py-4">
    <h5>📞 Get in Touch</h5>
    <p>📍 123, BuildBond Street, Jetpur-Gujarat, India</p>
    <p>📧 Email: <a href="mailto:BuildBond1@gmail.com" class="text-warning">BuildBond1@gmail.com</a></p>
    <p>📞 Call: <a href="tel:+919876543210" class="text-warning">+91 844 844 4856</a></p>
    <div>
        <a href="privacy-policy.php" class="text-light me-3">Privacy Policy</a> | 
        <a href="terms-conditions.php" class="text-light">Terms & Conditions</a>
    </div>
    <p class="mt-3">&copy; 2025 BuildBond - All Rights Reserved.</p>
</footer>


</body>
</html>
