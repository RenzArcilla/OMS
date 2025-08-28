<?php
/*
    This file is the controller file for restoring a disabled applicator.
    It retrieves form data, sanitizes it, and updates the database record.
*/


session_start();

require_once '../includes/js_alert.php';
require_once '../includes/db.php';
require_once '../models/update_applicator.php';

// Redirect url
$redirect_url = "../views/dashboard_applicator.php";

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method!", $redirect_url);
    exit;
}

// Check for credentials 
if (isset($_SESSION['user_type']) != "ADMIN") {
    jsAlertRedirect("You do not have the right permissions" . var_dump($_SESSION), "../views/login.php");
    exit;
}

// Get the form data
$applicator_id = isset($_POST['applicator_id']) ? trim($_POST['applicator_id']) : null;

// Check if fields are empty
if (empty($applicator_id)) {
    jsAlertRedirect("Missing required fields.", $redirect_url);
    exit;
}

try {
    $pdo->beginTransaction();

    $result = restoreDisabledApplicator($applicator_id);
    if ($result === true) { 
        $pdo->commit();
        jsAlertRedirect("applicator restored successfully!", $redirect_url);
        exit;
    } elseif (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    } else {
        $pdo->rollBack();
        jsAlertRedirect("Failed to restore applicator. Please try again.", $redirect_url);
        exit;
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    jsAlertRedirect("Database transaction failed: " . htmlspecialchars($e->getMessage()), $redirect_url);
    exit;
}
