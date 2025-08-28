<?php
/*
    This file is the controller file for restoring a disabled machine.
    It retrieves form data, sanitizes it, and updates the database record.
    - restore machine
    - restore outputs of the machine
    - restore cumulative outputs of the machine
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Include necessary files
require_once '../includes/js_alert.php';
require_once '../models/update_machine.php';
require_once '../models/update_machine_output.php';
require_once '../models/update_monitor_machine.php';

// Redirect url
$redirect_url = "../views/dashboard_machine.php";

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
$machine_id = isset($_POST['machine_id']) ? strtoupper(trim($_POST['machine_id'])) : null;

// 2. Validation
if (empty($machine_id)) {
    jsAlertRedirect("Machine ID is required.", $redirect_url);
    exit;
}

// 3. Database operation
try {
    $pdo->beginTransaction();

    // restores machine
    $restoreMachine = restoreDisabledMachine($machine_id);
    if (is_string($restoreMachine)) {
        throw new Exception($restoreMachine);
    }

    // restores outputs of the machine
    $restoreOutputs = restoreMachineOutputs($machine_id);
    if (is_string($restoreOutputs)) {
        throw new Exception($restoreOutputs);
    }

    // restores cumulative outputs of the machine
    $restoreCumulativeOutputs = restoreMachineCumulativeOutputs($machine_id);
    if (is_string($restoreCumulativeOutputs)) {
        throw new Exception($restoreCumulativeOutputs);
    }

    // Check if all returned true
    if ($restoreMachine && $restoreOutputs && $restoreCumulativeOutputs) {
        $pdo->commit();
        jsAlertRedirect("Machine restored successfully!", $redirect_url);
        exit;
    } else {
        throw new Exception("Failed to restore machine. Please try again.");
    }

} catch (Exception $e) {
    // Rollback on any error
    $pdo->rollBack();
    jsAlertRedirect($e->getMessage(), $redirect_url);
    exit;
}
