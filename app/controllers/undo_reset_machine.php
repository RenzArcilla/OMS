<?php
/*
    This script handles the undoing of reset of a machine part output.
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
require_once '../includes/db.php';
require_once '../models/read_machine_reset.php';
require_once '../models/update_machine_reset.php';
require_once '../models/update_monitor_machine.php';
require_once '../models/delete_record.php';
require_once '../models/read_records.php';
require_once '../models/delete_applicator_output.php';
require_once '../models/delete_machine_output.php';


// Redirect url
$redirect_url = "../views/dashboard_machine.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$machine_id = isset($_POST['machine_id']) && $_POST['machine_id'] !== '' 
    ? (int) $_POST['machine_id'] 
    : null;
$part_name = isset($_POST['part_name']) ? trim($_POST['part_name']) : null;
$reset_time = isset($_POST['reset_time']) ? trim($_POST['reset_time']) : null;

// 2. Validation
if ($machine_id === null || $part_name === '' || $reset_time === '') {
    jsAlertRedirect("Missing required fields.", $redirect_url);
    exit;
}

// Get user_id
$undone_by = $_SESSION['user_id'];

// 3. Database operation
try {
    $pdo->beginTransaction();

    // Fetch the reset data
    $data =  getmachineResetOnTimeStamp($machine_id, $part_name, $reset_time);
    if (is_string($data)) {
        $pdo->rollBack();
        jsAlertRedirect($data, $redirect_url); // error message
        exit;
    }

    // Get previous output value
    $previous_output = $data['previous_value'] ?? null;
    if ($previous_output === null) {
        $pdo->rollBack();
        jsAlertRedirect("Previous output not found.", $redirect_url);
        exit;
    }

    // Update the reset data to include undo data
    $result = updatemachineReset($machine_id, $part_name, $reset_time, 
                    $undone_by);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url); // error message
        exit;
    }

    // Update machine monitoring table to revert a machine part's output to value before reset
    $result = editMachinePartOutputValue($machine_id, $part_name, $previous_output);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url); // error message
        exit;
    }

    // Get records later than the timestamp 
    $data = getRecordsLaterThanTimestamp($reset_time);
    if (is_string($data)) {
        $pdo->rollBack();
        jsAlertRedirect($data, $redirect_url); // error message
        exit;
    }

    // Store all record_ids that were disabled
    $disabled_records = [];
    foreach ($data as $row) {
        $disabled_records[] = $row['record_id'];
    }

    // Disable records later than the timestamp (soft delete)
    $result = disableRecordEncodedLaterThan($reset_time);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url); // error message
        exit;
    }

    if (!empty($disabled_records)) {
        // Disable machine_outputs pertaining to all records
        $result = disablemachineOutputsByRecordIds($disabled_records);
        if (is_string($result)) {
            $pdo->rollBack();
            jsAlertRedirect($result, $redirect_url); // error message
            exit;
        }

        // Disable machine_outputs pertaining to all records
        $result = disableMachineOutputsByRecordIds($disabled_records);
        if (is_string($result)) {
            $pdo->rollBack();
            jsAlertRedirect($result, $redirect_url); // error message
            exit;
        }
    }

    // No failures occured, undo operation successful
    $pdo->commit();
    jsAlertRedirect("Reset undone! All records later than the timestamp have been disabled.", $redirect_url . "?filter_by=last_updated");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    jsAlertRedirect("Unexpected error: " . $e->getMessage(), $redirect_url);
    exit;
}
