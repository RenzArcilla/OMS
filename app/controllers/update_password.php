<?php
/*
    This script handles password updates for the current logged-in user.
    It validates the current password, checks the new password strength, and updates the database.
*/

// Include necessary files
require_once __DIR__ . '/../includes/auth.php'; // Authentication
require_once __DIR__ . '/../includes/db.php'; // Database connection
require_once __DIR__ . '/../includes/js_alert.php'; // JavaScript alert function
require_once __DIR__ . '/../models/update_password.php'; // Update password model

// Require user to be logged in
requireDefault();

// Redirect URL
$redirect_url = "../views/account_settings.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// Get current user ID from session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    jsAlertRedirect("User session not found. Please log in again.", $redirect_url);
    exit;
}

// 1. Sanitize input
$current_password = isset($_POST['currentPassword']) ? trim($_POST['currentPassword']) : null;
$new_password = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : null;
$confirm_password = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : null;

// 2. Validation
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    jsAlertRedirect("Please fill in all password fields.", $redirect_url);
    exit;
}

// Check if new password and confirmation match
if ($new_password !== $confirm_password) {
    jsAlertRedirect("New password and confirmation do not match.", $redirect_url);
    exit;
}

// Validate password strength
if (strlen($new_password) < 8) {
    jsAlertRedirect("Password must be at least 8 characters long.", $redirect_url);
    exit;
}

// Check for password complexity (at least one uppercase, lowercase, and number)
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $new_password)) {
    jsAlertRedirect("Password must contain at least one uppercase letter, one lowercase letter, and one number.", $redirect_url);
    exit;
}

// 3. Verify current password
$result = verifyCurrentPassword($user_id, $current_password);
if ($result !== true) {
    jsAlertRedirect($result, $redirect_url);
    exit;
}

// 4. Update password in the database
$result = updateUserPassword($user_id, $new_password);

if ($result === true) {
    jsAlertRedirect("Password updated successfully!", $redirect_url);
    exit;
} else {
    jsAlertRedirect($result, $redirect_url);
    exit;
}
