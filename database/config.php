<!-- 

CREATE DATABASE auth;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-->

<?php
// Check if running on localhost
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Local configuration
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'auth';
} else {
    // Online configuration
    $host = 'sql207.infinityfree.com';
    $username = 'if0_38845830';
    $password = 'Harsh992599';
    $dbname = 'if0_38845830_auth';
}

// Create connection
$con = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    error_log("Database Connection Error: " . $con->connect_error);
    die("Sorry, we couldn't connect to the database.");
}

// Set charset for proper encoding
$con->set_charset("utf8mb4");
?>  