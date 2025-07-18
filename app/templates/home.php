<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Show user information for the mean-time
echo "<h1>Welcome to the Home Page</h1>";
echo "<p>User ID: " . htmlspecialchars($_SESSION['user_id']) . "</p>";
?>