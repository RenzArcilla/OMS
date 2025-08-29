<?php
/*
    This script handles the disabling (soft delete) of a machine to the database.
    It retrieves form data, sanitizes it, and updates the status in the database.
    - disables machine
    - disables outputs of the machine
    - disable cumulative outputs of the machine
*/


// Include necessary files
require_once '../includes/auth.php';
require_once '../includes/js_alert.php';
require_once '../models/delete_machines.php';
require_once '../models/update_machine_output.php';
require_once '../models/update_monitor_machine.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/add_entry.php";

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

    // disables machine
    $disableMachine = disableMachine($machine_id);
    if (is_string($disableMachine)) {
        throw new Exception($disableMachine);
    }

    // disables outputs of the machine
    $disableOutputs = disableMachineOutputs($machine_id);
    if (is_string($disableOutputs)) {
        throw new Exception($disableOutputs);
    }

    // disable cumulative outputs of the machine
    $disableCumulativeOutputs = disableMachineCumulativeOutputs($machine_id);
    if (is_string($disableCumulativeOutputs)) {
        throw new Exception($disableCumulativeOutputs);
    }

    // Check if all returned true
    if ($disableMachine && $disableOutputs && $disableCumulativeOutputs) {
        $pdo->commit();
        jsAlertRedirect("Machine disabled successfully!", $redirect_url);
        exit;
    } else {
        throw new Exception("Failed to disable machine. Please try again.");
    }

} catch (Exception $e) {
    // Rollback on any error
    $pdo->rollBack();
    jsAlertRedirect($e->getMessage(), $redirect_url);
    exit;
}
