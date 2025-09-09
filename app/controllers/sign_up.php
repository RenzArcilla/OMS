<?php
/*
    This file is part of the sign-in operation for user accounts.
    It includes the database connection and processes the signin/signup form submission.
*/

require_once __DIR__ . '/../includes/js_alert.php'; // Include the JavaScript alert function
require_once __DIR__ . '/../includes/db.php'; // Include the database connection

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

// Validation Helpers: Username
function validateUsername($username) {
    if (preg_match('/\s/', $username)) {
        failRedirect("Username cannot contain spaces.");
    }
    if (strlen($username) < 5 || strlen($username) > 20) {
        failRedirect("Username must be between 5 and 20 characters long.");
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        failRedirect("Username can only contain letters, numbers, and underscores.");
    }
}

// Validation Helpers: Password
function validatePassword($password) {
    if (strlen($password) < 8) {
        failRedirect("Password must be at least 8 characters long.");
    }
    if (!preg_match('/[0-9]/', $password)) {
        failRedirect("Password must contain at least one number.");
    }
    if (!preg_match('/[\W_]/', $password)) {
        failRedirect("Password must contain at least one special character.");
    }
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

    // Run validations
    validateUsername($username);
    validatePassword($password);

} else {
    // Regular signup: require confirm_password
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($confirm_password)) {
        failRedirect("Please fill in all required fields.");
    }

    // Run validations
    validateUsername($username);
    validatePassword($password);

    // Password confirmation check
    if ($password !== $confirm_password) {
        failRedirect("Error! Password Mismatch!");
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
    
    $pdo->commit();
    if (isset($_POST['admin_create_user'])) {
        jsAlertRedirect("User created successfully.", $redirect_url);
        exit;
    } elseif ($user_type == 'TOOLKEEPER') {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['first_name'] = $result['first_name'];
        $_SESSION['user_type'] = $result['user_type'];
        jsAlertRedirect("Welcome back, Toolkeeper!", "../views/home.php");
    } elseif ($user_type == 'ADMIN') {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['first_name'] = $result['first_name'];
        $_SESSION['user_type'] = $result['user_type'];
        jsAlertRedirect("Welcome back, Admin!", "../views/manage_user.php");
        exit;
    } else {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $result['username'];
        $_SESSION['first_name'] = $result['first_name'];
        $_SESSION['user_type'] = $result['user_type'];
        jsAlertRedirect("Welcome to OMS!", "../views/home.php");
        exit;
    }
    exit();
} elseif (is_string($result)) {
    $pdo->rollBack();
    failRedirect($result);
} else {
    $pdo->rollBack();
    failRedirect("Registration failed. Please try again.");
}