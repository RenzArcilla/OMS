<?php
/*
Database connection script for the application
This script establishes a connection to the MySQL database using MySQLi.
It is included in other scripts to ensure database operations can be performed.
*/

$host = 'localhost'; // or change by DB server
$user = 'root';      // DB username
$pass = '';          // DB password
$db   = 'machine_and_applicator'; // DB name

// Create connection
try {
    // Connect to database with PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    // Throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Show alert if connection fails
    echo "<script>alert('Connection failed: " . addslashes($e->getMessage()) . "');</script>";
    exit();
}
?>