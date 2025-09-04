<?php
/*
    This file is the controller file for setting the output of a machine part to zero (0).
    It retrieves form data, sanitizes it, and updates the database record.
*/


// Include necessary files
require_once '../includes/auth.php';
require_once '../includes/js_alert.php';
require_once '../includes/db.php';
require_once '../models/read_monitor_machine.php';
require_once '../models/create_machine_reset.php';
require_once '../models/update_monitor_machine.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/dashboard_machine.php";

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method!", $redirect_url);
    exit;
}

// Get the form data
$part_name = isset($_POST['part_name']) ? trim($_POST['part_name']) : null;
$machine_id = isset($_POST['machine_id']) ? trim($_POST['machine_id']) : null;

// Debug logging
error_log("Reset machine request - Machine ID: $machine_id, Part: $part_name");

// Check if fields are empty
if (empty($part_name) || empty($machine_id)) {
    jsAlertRedirect("Missing required fields.", $redirect_url);
    exit;
}

// Get other needed arguments
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    jsAlertRedirect("Session expired. Please log in again.", "../views/login.php");
    exit;
}
$previous_value = getMonitorMachinePartOutput($machine_id, $part_name);
if (is_string($previous_value)) {
    jsAlertRedirect($previous_value, $redirect_url);
    exit;
}

try {
    $pdo->beginTransaction();

    $result = createMachineReset($user_id, $machine_id, $part_name, $previous_value);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    } 

    $result = resetMachinePartOutput($machine_id, $part_name);
    if ($result === true) {
        $pdo->commit();
        jsAlertRedirect("Part output reset successful!", $redirect_url . "?filter_by=last_updated");
        exit;
    } elseif (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    } else {
        $pdo->rollBack();
        jsAlertRedirect("Failed to update machine. Please try again.", $redirect_url);
        exit;
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    jsAlertRedirect("Database transaction failed: " . htmlspecialchars($e->getMessage()), $redirect_url);
    exit;
}
