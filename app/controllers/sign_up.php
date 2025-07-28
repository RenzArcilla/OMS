<?php
/*
    This file is part of the application for user registration.
    It handles the sign-in operation for user accounts.
*/

// Reteieve form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize input
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : Null;
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : Null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : Null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : Null;
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : Null;

    // Check if fields are empty
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Please fill in all fields.');
            window.location.href = '../views/soms_signup.php';</script>";
    } else {
        // Try to register the user
        include_once '../models/create_user.php';

        $result = createUser($firstname, $lastname, $username, $password, $confirm_password);

        // Check if user creation was successful
        if (is_array($result)) {
            //  Save user data into session to log them in immediately
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['first_name'] = $result['first_name'];
            $_SESSION['user_type'] = $result['user_type'];
            header("Location: /SOMS/app/views/home.php");
            exit();
        } elseif (is_string($result)) {
            echo $result; // Display error message from createUser function
        } else {
            echo "<script>alert('Registration failed. Please try again.');
                window.location.href = '../views/signup.php';</script>";
        }
    }
}