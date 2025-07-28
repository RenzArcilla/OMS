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
        echo "<script>alert('Please fill in all fields.');
            window.location.href = '../views/login.php';</script>";
    } else {
        // Try logging in the user
        include_once '../models/read_user.php';

        $result = loginUser($username, $password);
        if (is_array($result)) {
            // Set session variables
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['first_name'] = $result['first_name'];
            $_SESSION['user_type'] = $result['user_type'];
            
            // Redirect to home page 
            header("Location: ../views/home.php");
            exit();
        } elseif (is_string($result)) {
            echo $result; // Display error message from createUser function
        } else {
            echo "<script>alert('Invalid credentials. Please try again.');
                window.location.href = '../views/signup.php';</script>";
        }
    }
}