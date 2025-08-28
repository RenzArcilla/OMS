<?php
/*
    This script handles the deletion (hard delete) of a machine to the database.
    This includes removing all associated data and records. 
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
require_once '../models/delete_machines.php';
require_once '../models/delete_record.php';

// Redirect url
$redirect_url = "../views/dashboard_machine.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$machine_id = isset($_POST['machine_id']) ? (int)trim($_POST['machine_id']) : null;

// 2. Validation
if (empty($machine_id)) {
    jsAlertRedirect("Machine ID is required.", $redirect_url);
    exit;
}

// 3. Database operation
try {
    $pdo->beginTransaction();

    // Delete machine
    $deleteMachine = deleteMachine($machine_id);
    if (is_string($deleteMachine)) {
        throw new Exception($deleteMachine);
    }

    // Delete machine outputs
    $deleteMachineOutput = deleteOutputByMachineID($machine_id);
    if (is_string($deleteMachineOutput)) {
        throw new Exception($deleteMachineOutput);
    }

    // If everything is successful, commit
    $pdo->commit();
    jsAlertRedirect("Machine and records disabled successfully.", $redirect_url);

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsAlertRedirect($e->getMessage(), $redirect_url);
    exit;
}