<?php
/*
    This file is part of the log in operation for user accounts.
    It includes the database connection and processes the login form submission.
*/


session_start(); 

require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/read_user.php';

// Get the form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    
    // Check if fields are empty
    if (empty($username) || empty($password)) {
        jsAlertRedirect("Please fill in all fields.", "../views/login.php");
        exit;
    }   

    // Try logging in the user
    $pdo->beginTransaction();
    $result = loginUser($username, $password);
    if (is_array($result)) {
        $pdo->commit();
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['first_name'] = $result['first_name'];
        $_SESSION['user_type'] = $result['user_type'];
        
        header("Location: ../views/home.php");
        exit();
    } elseif (is_string($result)) {
        $pdo->rollBack(); // Rollback transaction in case of error
        jsAlertRedirect($result, "../views/login.php");
        exit;
    } else {
        $pdo->rollBack();
        jsAlertRedirect("Invalid credentials. Please try again.", "../views/login.php");
        exit;
    }
}