<?php
/*
    This file is the controller file for restoring a disabled applicator.
    It retrieves form data, sanitizes it, and updates the database record.
    - restore applicator
    - restore outputs of the applicator
    - restore cumulative outputs of the applicator
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Include necessary files
require_once '../includes/js_alert.php';
require_once '../models/update_applicator.php';
require_once '../models/update_applicator_output.php';
require_once '../models/update_monitor_applicator.php';

// Redirect url
$redirect_url = "../views/dashboard_applicator.php";

// Check for credentials 
if (isset($_SESSION['user_type']) != "ADMIN") {
    jsAlertRedirect("You do not have the right permissions" . var_dump($_SESSION), "../views/login.php");
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$applicator_id = isset($_POST['applicator_id']) ? strtoupper(trim($_POST['applicator_id'])) : null;

// 2. Validation
if (empty($applicator_id)) {
    jsAlertRedirect("applicator ID is required.", $redirect_url);
    exit;
}

// 3. Database operation
try {
    $pdo->beginTransaction();

    // restores applicator
    $restoreApplicator = restoreDisabledApplicator($applicator_id);
    if (is_string($restoreApplicator)) {
        throw new Exception($restoreApplicator);
    }

    // restores outputs of the applicator
    $restoreOutputs = restoreApplicatorOutputs($applicator_id);
    if (is_string($restoreOutputs)) {
        throw new Exception($restoreOutputs);
    }

    // restores cumulative outputs of the applicator
    $restoreCumulativeOutputs = restoreApplicatorCumulativeOutputs($applicator_id);
    if (is_string($restoreCumulativeOutputs)) {
        throw new Exception($restoreCumulativeOutputs);
    }

    // Check if all returned true
    if ($restoreApplicator && $restoreOutputs && $restoreCumulativeOutputs) {
        $pdo->commit();
        jsAlertRedirect("Applicator restored successfully!", $redirect_url);
        exit;
    } else {
        throw new Exception("Failed to restore applicator. Please try again.");
    }

} catch (Exception $e) {
    // Rollback on any error
    $pdo->rollBack();
    jsAlertRedirect($e->getMessage(), $redirect_url);
    exit;
}
