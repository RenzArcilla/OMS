<?php
/*
    This file is part of the application for user registration.
    It handles the sign-in operation for user accounts.
*/

require_once '../includes/js_alert.php'; // Include the JavaScript alert function
require_once '../includes/db.php'; // Include the database connection

// Redirect url
$redirect_url = "../views/signup.php";

// Quick Fail
function failRedirect($message) {
    jsAlertRedirect($message, $redirect_url);
    exit;
}

// Retrieve form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize input
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : null;
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : Null;

    // 2. Validation
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($confirm_password)) {
        failRedirect("Please fill in all fields.");
    }
    
    if ($password !== $confirm_password) {
        // Password confirmation check
        failRedirect("Password Mismatch!");
    }

    // 3. Try to register the user
    include_once '../models/create_user.php';

    $result = createUser($firstname, $lastname, $username, $password, $confirm_password);

    // 4. Handle response
    if (is_array($result)) {
        //  Save user data into session to log them in immediately
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['first_name'] = $result['first_name'];
        $_SESSION['user_type'] = $result['user_type'];
        header("Location: ../views/home.php");
        exit();
    } elseif (is_string($result)) {
        failRedirect($result);
    } else {
        failRedirect("Registration failed. Please try again.");
    }
} 