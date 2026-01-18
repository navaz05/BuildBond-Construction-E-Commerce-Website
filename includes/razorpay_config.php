<?php
// Razorpay Configuration
define('RAZORPAY_KEY_ID', 'rzp_test_YooygdVXgo9cGg');
define('RAZORPAY_KEY_SECRET', 'yXHXCSVx0R0RIMSd13PAk3xz');

// Check if Composer's autoload file exists
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die('Razorpay SDK is not installed. Please run "composer install" to install the required dependencies.');
}

// Include Razorpay SDK
require_once $autoloadPath;

use Razorpay\Api\Api;

// Create Razorpay API instance
try {
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
} catch (Exception $e) {
    die('Error initializing Razorpay API: ' . $e->getMessage());
}
?> 