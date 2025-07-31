<?php
/*
    This script handles the deletion (soft delete) of an applicator to the database.
    It retrieves form data, sanitizes it, and updates the status in the database.
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Include necessary files
require_once '../includes/js_alert.php';
include_once '../models/delete_applicator.php';

// Redirect url
$redirect_url = "../views/add_entry.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$applicator_id = isset($_POST['applicator_id']) ? strtoupper(trim($_POST['applicator_id'])) : null;

// 2. Validation
if (empty($applicator_id)) {
    jsAlertRedirect("Applicator ID is required.", $redirect_url);
    exit;
}

// 3. Database operation
$result = disableApplicator($applicator_id);

// Check if applicator deletion was successful
if ($result === true) {
    jsAlertRedirect("Applicator deleted successfully!", $redirect_url);
    exit;
} elseif (is_string($result)) {
    jsAlertRedirect($result, $redirect_url);
    exit;
} else {
    jsAlertRedirect("Failed to delete applicator. Please try again.", $redirect_url);
    exit;
}
