<?php
/*
    This script handles the disabling (soft delete) of a record in the database.
    It retrieves form data, sanitizes it, and updates the status in the database.
*/


// Include necessary files
require_once '../includes/auth.php';
require_once '../includes/js_alert.php';
require_once '../models/delete_record.php';
require_once '../models/delete_applicator_output.php';
require_once '../models/delete_machine_output.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/record_output.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$record_id = isset($_POST['record_id']) ? (int) trim($_POST['record_id']) : null;

// 2. Validation
if (empty($record_id)) {
    jsAlertRedirect("Record ID is required.", $redirect_url);
    exit;
}

// 3. Database operation
try {
    $pdo->beginTransaction();

    // Disable the record
    $result = disableRecord($record_id);
    if (is_string($result)) throw new Exception($result);

    // Disable applicator_outputs pertaining to the record
    $result = disableApplicatorOutput($record_id);
    if (is_string($result)) throw new Exception($result);

    // Disable machine_output pertaining to the record
    $result = disableMachineOutput($record_id);
    if (is_string($result)) throw new Exception($result);

    // Commit transaction
    $pdo->commit();
    jsAlertRedirect("Record deleted successfully!", $redirect_url);
    exit;

} catch (Exception $e) {
    // Rollback on any error
    $pdo->rollBack();
    jsAlertRedirect("Database Error: " . $e->getMessage(), $redirect_url);
    exit;
}
