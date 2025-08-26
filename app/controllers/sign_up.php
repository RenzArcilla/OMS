<?php
/*
    This file is part of the sign-in operation for user accounts.
    It includes the database connection and processes the signin/signup form submission.
*/

require_once '../includes/js_alert.php'; // Include the JavaScript alert function
require_once '../includes/db.php'; // Include the database connection

// Redirect URL
$redirect_url = "../views/signup.php";
if (isset($_POST['admin_create_user'])) {
    $redirect_url = "../views/manage_user.php";
}

// Quick Fail
function failRedirect($message) {
    global $redirect_url;
    jsAlertRedirect($message, $redirect_url);
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    failRedirect("Invalid request.");
}

// 1. Sanitize input
$firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : null;
$lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : null;
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;
$role = isset($_POST['role']) ? trim($_POST['role']) : null;

// 2. Validation
if (isset($_POST['admin_create_user'])) {
    // Admin create user: don't require confirm_password
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($role)) {
        failRedirect("Please fill in all required fields.");
    }
} else {
    // Regular signup: require confirm_password
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($confirm_password)) {
        failRedirect("Please fill in all required fields.");
    }
    // Password confirmation check
    if ($password !== $confirm_password) {
        failRedirect("Password Mismatch!");
    }
}

// 3. Normalize role
switch (strtolower($role)) {
    case 'admin':
        $role = 'ADMIN';
        break;
    case 'toolkeeper':
        $role = 'TOOLKEEPER';
        break;
    case 'default':
    case 'user':
    default:
        $role = 'DEFAULT';
        break;
}

// 4. Try to register the user
$pdo->beginTransaction();
include_once '../models/create_user.php';

$result = createUser($firstname, $lastname, $username, $password, $role);

// 5. Handle response
if (is_array($result)) {
    // Save user data into session to log them in immediately
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['user_id'] = $result['user_id'];
    $_SESSION['username'] = $result['username'];
    $_SESSION['first_name'] = $result['first_name'];
    $_SESSION['user_type'] = $result['user_type'];
    $pdo->commit();
    if (isset($_POST['admin_create_user'])) {
        jsAlertRedirect("User created successfully.", $redirect_url);
    } elseif ($user_type == 'TOOLKEEPER') {
        header("Location: ../views/record_output.php");
    } else {
        header("Location: ../views/dashboard_applicator.php");
    }
    exit();
} elseif (is_string($result)) {
    $pdo->rollBack();
    failRedirect($result);
} else {
    $pdo->rollBack();
    failRedirect("Registration failed. Please try again.");
}