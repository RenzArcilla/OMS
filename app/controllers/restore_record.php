<?php
/*
    This file is the controller file for restoring a disabled record.
    It retrieves form data, sanitizes it, and updates the database entry.
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../models/update_record.php';
require_once __DIR__ . '/../models/update_applicator_output.php';
require_once __DIR__ . '/../models/update_machine_output.php';

// Require Admin Privileges
requireAdmin();

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

    // Restore the record
    $restoreRecord = restoreRecord($record_id);
    if (is_string($restoreRecord)) throw new Exception($restoreRecord);

    // Restore applicator_outputs pertaining to the record
    $restoreApplicatorOutput = restoreApplicatorOutputByRecordID($record_id);
    if (is_string($restoreApplicatorOutput)) throw new Exception($restoreApplicatorOutput);

    // Restore machine_output pertaining to the record
    $restoreMachineOutput = restoreMachineOutputByRecordID($record_id);
    if (is_string($restoreMachineOutput)) throw new Exception($restoreMachineOutput);

    // Commit transaction
    if ($restoreRecord && $restoreApplicatorOutput && $restoreMachineOutput) {
        $pdo->commit();
        jsAlertRedirect("Record restored successfully!", $redirect_url);
        exit;
    }

} catch (Exception $e) {
    // Rollback on any error
    $pdo->rollBack();
    jsAlertRedirect("Database Error: " . $e->getMessage(), $redirect_url);
    exit;
}
