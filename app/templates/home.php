<?php
/*
    This is the home page of the application. It serves as the landing page for logged-in users.
    It includes a hero section with a brief description of the system and a call to action for accessing the dashboard or signing up.
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


include_once __DIR__ . '/../includes/header.php'; // Include the header file for the navigation and logo
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Hero</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/hero.css">
</head>
<body>
    <h1>Storage and Output Monitoring System</h1>
    <a href="add_entry.php">Add-Record</a>
</body>
</html>