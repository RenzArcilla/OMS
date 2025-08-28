<?php
/*
    This file is the controller file for restoring a disabled record.
    It retrieves form data, sanitizes it, and updates the database entry.
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Include necessary files
require_once '../includes/js_alert.php';
require_once '../models/update_record.php';
require_once '../models/update_applicator_output.php';
require_once '../models/update_machine_output.php';

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
    $result = restoreRecord($record_id);
    if (is_string($result)) throw new Exception($result);

    // Restore applicator_outputs pertaining to the record
    $result = restoreApplicatorOutput($record_id);
    if (is_string($result)) throw new Exception($result);

    // Restore machine_output pertaining to the record
    $result = restoreMachineOutput($record_id);
    if (is_string($result)) throw new Exception($result);

    // Commit transaction
    $pdo->commit();
    jsAlertRedirect("Record restored successfully!", $redirect_url);
    exit;

} catch (Exception $e) {
    // Rollback on any error
    $pdo->rollBack();
    jsAlertRedirect("Database Error: " . $e->getMessage(), $redirect_url);
    exit;
}
