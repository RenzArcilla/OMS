<?php
/*
    This script handles the undoing of reset of an applicator part output.
    It retrieves form data, sanitizes it, and updates the status in the database.
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/read_applicator_reset.php';
require_once __DIR__ . '/../models/update_applicator_reset.php';
require_once __DIR__ . '/../models/update_monitor_applicator.php';
require_once __DIR__ . '/../models/delete_record.php';
require_once __DIR__ . '/../models/read_records.php';
require_once __DIR__ . '/../models/delete_applicator_output.php';
require_once __DIR__ . '/../models/delete_machine_output.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/dashboard_applicator.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$applicator_id = isset($_POST['applicator_id']) && $_POST['applicator_id'] !== '' 
    ? (int) $_POST['applicator_id'] 
    : null;
$part_name = isset($_POST['part_name']) ? trim($_POST['part_name']) : null;
$reset_time = isset($_POST['reset_time']) ? trim($_POST['reset_time']) : null;

// 2. Validation
if ($applicator_id === null || $part_name === '' || $reset_time === '') {
    jsAlertRedirect("Missing required fields.", $redirect_url);
    exit;
}

// Get user_id
$undone_by = $_SESSION['user_id'];

// 3. Database operation
try {
    $pdo->beginTransaction();

    // Fetch the reset data
    $data =  getApplicatorResetOnTimeStamp($applicator_id, $part_name, $reset_time);
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
    $result = updateApplicatorReset($applicator_id, $part_name, $reset_time, 
                    $undone_by);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url); // error message
        exit;
    }

    // Update applicator monitoring table to revert an applicator part's output to value before reset
    $result = editApplicatorPartOutputValue($applicator_id, $part_name, $previous_output);
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
        // Disable applicator_outputs pertaining to all records
        $result = disableApplicatorOutputsByRecordIds($disabled_records);
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
