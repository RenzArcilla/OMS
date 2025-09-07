<?php
/*
    Database connection script for the application
    This script establishes a connection to the MySQL database using MySQLi.
    It is included in other scripts to ensure database operations can be performed.
*/

require_once __DIR__ . '/config.php'; // Include the configuration file for database settings

// Create connection
try {
    // Connect to database with PDO
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    // Throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Show alert if connection fails
    $error = "Connection failed: " . $e->getMessage();
    require_once __DIR__ . '/js_alert.php';
    jsAlertRedirect($error, "../views/home.php");
    exit;
}