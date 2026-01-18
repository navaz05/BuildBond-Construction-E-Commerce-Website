<?php include 'navbar-top.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuildBond - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* Slideshow Styles */
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-content {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 80%;
            text-align: center;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.8s ease-in-out 0.5s;
            z-index: 5;
        }

        .slide.active .slide-content {
            transform: translateY(0);
            opacity: 1;
        }

        .slide-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fff;
        }

        .slide-content p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .slide-content .btn {
            background-color: #05a39c;
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
            display: inline-block;
            text-decoration: none;
        }

        .slide-content .btn:hover {
            background-color: #048a83;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .slideshow-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .slideshow-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slideshow-dot.active {
            background-color: #05a39c;
            transform: scale(1.2);
        }

        /* Progress bar for automatic slideshow */
        .slideshow-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background-color: #05a39c;
            width: 0;
            transition: width 0.1s linear;
        }

        .slideshow-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.3);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .slideshow-arrow:hover {
            background-color: rgba(5, 163, 156, 0.7);
        }

        .slideshow-prev {
            left: 20px;
        }

        .slideshow-next {
            right: 20px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .slideshow-container {
                height: 400px;
            }

            .slide-content {
                padding: 20px;
                max-width: 90%;
            }

            .slide-content h2 {
                font-size: 1.8rem;
            }

            .slide-content p {
                font-size: 1rem;
            }

            .slideshow-arrow {
                width: 30px;
                height: 30px;
            }
        }

        @media (max-width: 576px) {
            .slideshow-container {
                height: 350px;
            }

            .slide-content h2 {
                font-size: 1.5rem;
            }

            .slide-content p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <!-- Slideshow Section -->
    <div class="slideshow-container">
        <!-- Progress bar for automatic slideshow -->
        <div class="slideshow-progress"></div>
        
        <!-- Slide 1 -->
        <div class="slide active" style="background-image: url('assets/3d-rendering-loft-luxury-living-room-with-bookshelf-near-bookshelf.jpg');">
            <div class="slide-content">
                <h2>Transform Your Living Space</h2>
                <p>Discover our premium collection of furniture and designs that will elevate your home to new heights of elegance and comfort.</p>
                <a href="product.php" class="btn">Shop Now</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide" style="background-image: url('assets/stylish-scandinavian-living-room-with-design-mint-sofa-furnitures-mock-up-poster-map-plants-eleg.jpg');">
            <div class="slide-content">
                <h2>Modern Scandinavian Design</h2>
                <p>Experience the perfect blend of functionality and aesthetics with our Scandinavian-inspired furniture collection.</p>
                <a href="design.php" class="btn">Explore Designs</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slide" style="background-image: url('assets/stylish-scandinavian-living-room-with-design-mint-sofa-furnitures-mock-up-poster-map-plants-eleg (1).jpg');">
            <div class="slide-content">
                <h2>Quality Craftsmanship</h2>
                <p>Every piece is crafted with precision and care, ensuring durability and timeless beauty for your home.</p>
                <a href="about.php" class="btn">Learn More</a>
            </div>
        </div>

        <!-- Slideshow Controls -->
        <div class="slideshow-controls">
            <div class="slideshow-dot active" data-slide="0"></div>
            <div class="slideshow-dot" data-slide="1"></div>
            <div class="slideshow-dot" data-slide="2"></div>
        </div>

        <!-- Arrow Controls -->
        <div class="slideshow-arrow slideshow-prev">
            <i class="bi bi-chevron-left"></i>
        </div>
        <div class="slideshow-arrow slideshow-next">
            <i class="bi bi-chevron-right"></i>
        </div>
    </div>
    
<?php
include 'database/config.php';
$sql = "SELECT * FROM products  WHERE category = 'product' LIMIT 3"; // Just 3 products
$result = mysqli_query($con, $sql);
?>
<div class="container mt-4">
    <h2 class="text-center fw-bold">Featured Products</h2>
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($result)) {
            include 'components/product_card.php';
        } ?>
    </div>
    <div class="text-center">
        <a href="product.php" class="btn" style="background-color:#05a39c; color:white;">View All Products</a>
    </div>
</div>

<?php
include 'database/config.php';
$sql = "SELECT * FROM products WHERE category = 'design' LIMIT 3"; // Just 3 products
$result = mysqli_query($con, $sql);
?>
<div class="container mt-4">
    <h2 class="text-center fw-bold">Featured Designs</h2>
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($result)) {
            include 'components/design_card.php';
        } ?>
    </div>
    <div class="text-center">
        <a href="design.php" class="btn" style="background-color: #05a39c; color:white;">View All Designs</a>
    </div>
</div>


<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Slideshow Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slideshow-dot');
        const prevBtn = document.querySelector('.slideshow-prev');
        const nextBtn = document.querySelector('.slideshow-next');
        const progressBar = document.querySelector('.slideshow-progress');
        let currentSlide = 0;
        let slideInterval;
        let progressInterval;
        const SLIDE_DURATION = 5000; // 5 seconds in milliseconds

        // Function to show a specific slide
        function showSlide(index) {
            // Remove active class from all slides and dots
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Add active class to current slide and dot
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            
            currentSlide = index;
            
            // Reset progress bar
            progressBar.style.width = '0%';
        }

        // Function to show next slide
        function nextSlide() {
            let nextIndex = currentSlide + 1;
            if (nextIndex >= slides.length) {
                nextIndex = 0;
            }
            showSlide(nextIndex);
        }

        // Function to show previous slide
        function prevSlide() {
            let prevIndex = currentSlide - 1;
            if (prevIndex < 0) {
                prevIndex = slides.length - 1;
            }
            showSlide(prevIndex);
        }

        // Start automatic slideshow
        function startSlideshow() {
            // Clear any existing intervals
            stopSlideshow();
            
            // Start the slide interval
            slideInterval = setInterval(function() {
                nextSlide();
            }, SLIDE_DURATION);
            
            // Start the progress bar animation
            let startTime = Date.now();
            progressInterval = setInterval(() => {
                let elapsedTime = Date.now() - startTime;
                let progress = (elapsedTime / SLIDE_DURATION) * 100;
                progressBar.style.width = progress + '%';
                
                if (progress >= 100) {
                    clearInterval(progressInterval);
                }
            }, 10);
        }

        // Stop automatic slideshow
        function stopSlideshow() {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }
        }

        // Event listeners for dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', (e) => {
                e.stopPropagation();
                showSlide(index);
                stopSlideshow();
                startSlideshow();
            });
        });

        // Event listeners for arrows
        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            prevSlide();
            stopSlideshow();
            startSlideshow();
        });

        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            nextSlide();
            stopSlideshow();
            startSlideshow();
        });

        // Pause slideshow when hovering over it
        const slideshowContainer = document.querySelector('.slideshow-container');
        slideshowContainer.addEventListener('mouseenter', stopSlideshow);
        slideshowContainer.addEventListener('mouseleave', startSlideshow);

        // Make sure buttons in slide content are clickable
        const slideButtons = document.querySelectorAll('.slide-content .btn');
        slideButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });

        // Start the slideshow
        startSlideshow();
        
        // Ensure slideshow continues even if tab is inactive
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopSlideshow();
            } else {
                startSlideshow();
            }
        });
        
        // Debug: Log when slides change
        console.log("Slideshow initialized with " + slides.length + " slides");
        console.log("Automatic slide change interval: " + SLIDE_DURATION + "ms");
    });
</script>

</body>
</html>
<?php include 'footer.php'; ?>