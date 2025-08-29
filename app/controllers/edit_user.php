<?php
/*
    This script handles the editing logic for an existing user in the database.
    It retrieves form data, sanitizes it, checks for duplicate usernames, and updates the user record.
*/


// Include necessary files
require_once '../includes/auth.php'; // Authentication
require_once '../includes/db.php'; // Database connection
require_once '../includes/js_alert.php'; // JavaScript alert function
require_once '../models/read_user.php'; // Read user model
require_once '../models/update_user.php'; // Update user model

// Require Admin Privileges
requireAdmin();

// Redirect URL
$redirect_url = "../views/manage_user.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : null;
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;
$role = isset($_POST['role']) ? trim($_POST['role']) : null;

// 2. Validation
if (empty($user_id) || empty($username) || empty($first_name) || empty($last_name) || empty($role)) {
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
}


// Validate role
$valid_roles = ['DEFAULT', 'TOOLKEEPER', 'ADMIN'];
if (!in_array($role, $valid_roles)) {
    jsAlertRedirect("Invalid role selected.", $redirect_url);
    exit;
}

// Validate username format (e.g., alphanumeric, 3-50 characters)
if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    jsAlertRedirect("Username must be 3-50 alphanumeric characters (underscores allowed).", $redirect_url);
    exit;
}

// 3. Check for duplicate username (excluding the current user)
$result = getUserByUsername($username);
if (is_string($result)) {
    jsAlertRedirect($result, $redirect_url);
    exit;
}

if ($result && $result['user_id'] != $user_id) {
    jsAlertRedirect("A user with username '$username' already exists.", $redirect_url);
    exit;
}

// 4. Hash password if provided
$password_hash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

// 5. Update user in the database
$pdo->beginTransaction();
$result = updateUser($user_id, $username, $first_name, $last_name, $password_hash, $role);

if ($result === true) {
    $pdo->commit();
    jsAlertRedirect("User updated successfully!", $redirect_url);
    exit;
} else {
    $pdo->rollBack();
    jsAlertRedirect($result, $redirect_url);
    exit;
}
