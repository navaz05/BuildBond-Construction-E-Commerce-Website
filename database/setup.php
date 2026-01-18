<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create a PDO connection without database name
    $pdo = new PDO("mysql:host=$host", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read the SQL file
    $sql = file_get_contents('setup_database.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "Database and tables created successfully!";
    
} catch(PDOException $e) {
    // Log the error and display a user-friendly message
    error_log("Database Setup Error: " . $e->getMessage());
    die("Sorry, there was a problem setting up the database. Please try again later.");
}
?> 