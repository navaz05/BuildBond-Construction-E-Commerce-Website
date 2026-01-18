<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Small Image Slider</title>

    <!-- Slick Slider CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick-theme.css"/>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0 !important;
            padding: 0 !important;
            text-align: center;
           
        }

        /* Slider Container */
        .small-slider-container {
            width: 70%;
            margin: 0 auto;  /* Centers the slider */
            padding: 0 !important ; /* Remove extra padding */
            height: 220px;
        }

        /* Slider */
        .small-slider {
            cursor: grab;
        }

        /* Grabbing Effect */
        .small-slider:active {
            cursor: grabbing;
        }

        /* Image Styling */
        .small-slider img {
            width: 100%;
            object-fit: contain;
            border-radius: 5px;
            pointer-events: none; /* Prevents unwanted selection */
        }

        /* Hide Arrows */
        .slick-prev, .slick-next {
            display: none !important;
        }

        /* Hide Dots */
        .slick-dots {
            display: none !important;
        }
    </style>
</head>
<body>

    <!-- Slider -->
    <div class="small-slider-container">
        <div class="small-slider">
            <div><img src="assets/s2/asset 12.png" alt="Image 1"></div>
            <div><img src="assets/s2/asset 13.png" alt="Image 2"></div>
            <div><img src="assets/s2/asset 14.png" alt="Image 3"></div>
            <div><img src="assets/s2/asset 15.png" alt="Image 4"></div>
            <div><img src="assets/s2/asset 16.png" alt="Image 5"></div>
            <div><img src="assets/s2/asset 17.png" alt="Image 6"></div>
        </div>
    </div>

    <!-- Slick Slider JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.small-slider').slick({
                autoplay: true,
                autoplaySpeed: 1000,
                infinite: true,
                speed: 500,
                slidesToShow: 5,
                slidesToScroll: 1,
                pauseOnHover: false,
                arrows: false
            });
        });
    </script>

</body>
</html>
