<?php
/*
    This script handles the deletion (soft delete) of a machine to the database.
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
require_once '../models/read_applicator_reset.php';
require_once '../models/update_applicator_reset.php';
require_once '../models/update_monitor_applicator.php';
require_once '../models/delete_record.php';


// Redirect url
$redirect_url = "../views/dashboard_applicator.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$applicator_id = isset($_POST['applicator_id']) ? (int) trim($_POST['applicator_id']) : null;
$part_name = isset($_POST['part_name']) ? trim($_POST['part_name']) : null;
$reset_time = isset($_POST['reset_time']) ? trim($_POST['reset_time']) : null;

// 2. Validation
if (empty($applicator_id) || empty($part_name) || empty($reset_time)) {
    jsAlertRedirect("Missing required fields.", $redirect_url);
    exit;
}

// Get user_id
$undone_by = $_SESSION['user_id'];

// 3. Database operation
$pdo->beginTransaction();

// Fetch the reset data
$data =  getApplicatorResetOnTimeStamp($applicator_id, $part_name, $reset_time);

if (is_string($data)) {
    $pdo->rollBack();
    jsAlertRedirect($data, $redirect_url); // error message
    exit;
}

$previous_output = $data['previous_value'];

// Update the reset data to include undo data
$result = updateApplicatorReset($applicator_id, $part_name, $reset_time, 
                $undone_by);

if (is_string($result)) {
    $pdo->rollBack();
    jsAlertRedirect($result, $redirect_url); // error message
    exit;
}

// Update applicator monitoring table to revert an applicator part's output to value before reset
$result = editPartOutputValue($applicator_id, $part_name, $previous_output);
if (is_string($result)) {
    $pdo->rollBack();
    jsAlertRedirect($result, $redirect_url); // error message
    exit;
}

// Delete records later than the timestamp (hard delete) 
// ON DELETE CASCADE - this will also delete individual machine and applicator output linked to the record
$result = deleteRecordEncodedLaterThan($reset_time);
if (is_string($result)) {
    $pdo->rollBack();
    jsAlertRedirect($result, $redirect_url); // error message
    exit;
}

// No failures occured, undo operation successful
$pdo->commit();
jsAlertRedirect("Reset undone! All records later than the timestamp have been deleted.", $redirect_url . "?filter_by=last_updated");
exit;
