
<?php include 'navbar-top.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <style>
        .btn-custom {
            background-color: #05a39c;
            color: white;
        }
        footer{
            background-color: #05a39c;
        }
        .btn-custom:hover {
            background-color: #03857f;
        }
    
    </style>
</head>
<body>

<div class="container mt-3 pb-3">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="border p-4 rounded shadow-sm bg-white">
                <h4 class="fw-bold">Create an Account</h4>
                <p class="text-muted">
                    Creating an account lets you easily track and return purchases, save time with expedited checkout, 
                    create lists of favorite products, keep track of your projects, and more.
                </p>
                
                <!-- Display Error or Success Messages -->
                <?php
                if(isset($_SESSION['signup_error']) && !empty($_SESSION['signup_error'])){
                    echo "<p style='color:red;'>".$_SESSION['signup_error']."</p>";
                    unset($_SESSION['signup_error']);
                }
                if(isset($_SESSION['signup_success']) && !empty($_SESSION['signup_success'])){
                    echo "<p style='color:green;'>".$_SESSION['signup_success']."</p>";
                    unset($_SESSION['signup_success']);
                }
                ?>

                <!-- Registration Form -->
                <form action="database/registerprocess.php" method="post" id="RegisterForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="First Name" required>
                        </div>

                         <div class="mb-3">
                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <small class="text-muted">Minimum of 6 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>

                    <button type="submit" class="btn btn-custom w-100" name="signup">Create Account</button>
                </form>

                <p class="mt-3 small text-muted">
                    This site is protected by reCAPTCHA and the Google 
                    <a href="privacy-policy.php" class="text-decoration-none">Privacy Policy</a> and 
                    <a href="terms-of-service.php" class="text-decoration-none">Terms of Service</a> apply.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript & Validation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    $(document).ready(function(){
        $("#RegisterForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                }
            },
            messages:{
                name: {
                    required: "Please enter your name",
                    minlength: "Name must be at least 3 characters long"
                },
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter a password",
                    minlength: "Password must be at least 6 characters long"
                },
                confirm_password: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            errorClass: "text-danger",
            submitHandler:function(form){
                form.submit();
            }
        });
    });
</script>
<footer class="text-white text-center py-4">
    <h5>🎉 Join BuildBond Today!</h5>
    <p>Get exclusive discounts, updates & offers.</p>
    <p><a href="faq.php" class="text-warning">FAQ</a> | <a href="privacy-policy.php" class="text-warning">Privacy Policy</a></p>
    <p>&copy; 2025 BuildBond - Your Trusted Partner.</p>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
