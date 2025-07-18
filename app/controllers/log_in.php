<?php
/*
    This file is part of log in operation for user accounts.
    It includes the database connection and processes the login form submission.
*/


session_start(); // Start session
            

// Get the form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        // Try logging in the user
        include_once '../models/READ_user.php';

        $result = loginUser($username, $password);
        if ($result) {
            // Set session variables
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            
            // Redirect to home page 
            header("Location: ../templates/home.php");
            exit();
        } else {
            echo "<script>alert('Invalid credentials. Please try again.');</script>";
        }
    }
}


?>