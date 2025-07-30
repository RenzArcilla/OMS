<?php
/*
    This file handles the recording of outputs for machines and applicators.
    It processes the form submission from the record output page and saves the data to the database.
*/

// Ensure the user is logged in before proceeding
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

// Include necessary files
require_once '../includes/js_alert.php';
require_once '../models/read_applicators.php';
require_once '../models/read_machines.php';
require_once '../models/create_record.php';
require_once '../models/create_applicator_output.php';
require_once '../models/create_machine_output.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", "../views/record_output.php");
    exit;
}

// --- Redirect path ---
$redirect = "../views/record_output.php";

// --- Helper: Fail during error ---
function fail($msg) {
    jsAlertRedirect($msg, $redirect);
    exit;
}

// --- Helper: Get Inputs ---
function getInput($key) {
    return strtoupper(trim($_POST[$key] ?? ''));
}

// 1. Sanitize input
$date = trim($_POST['date_inspected'] ?? '');
$shift = getInput('shift');
$app1 = getInput('app1');
$app1_out = (int)(getInput('app1_output') ?? 0);
$app2 = getInput('app2');
$app2_out = (int)(getInput('app2_output') ?? 0);
$machine = getInput('machine');
$machine_out = (int)(getInput('machine_output') ?? 0);

// 2. Validation
if (!$date || !$shift || !$app1 || !$app1_out || !$machine || !$machine_out) {
    fail("Please fill in all required fields.");
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    fail("Invalid date format.");
}
if (!in_array($shift, ['FIRST', 'SECOND', 'NIGHT'])) {
    fail("Invalid shift.");
}

try {
    $date = (new DateTime($date))->format('Y-m-d');
} catch (Exception $e) {
    fail("Invalid date value.");
}

// 3. Check Existence
$app1_data = applicatorExists($app1);
if (!$app1_data) fail("Applicator 1 not found.");
if (is_string($app1_data)) fail($app1_data);

$app2_data = null;
if (!empty($app2)) {
    $app2_data = applicatorExists($app2);
    if (!$app2_data) fail("Applicator 2 not found.");
    if (is_string($app2_data)) fail($app2_data);
}

$machine_data = machineExists($machine);
if (!$machine_data) fail("Machine not found.");
if (is_string($machine_data)) fail($machine_data);

// 4. Database operation
// Create the record
$record_id = createRecord($shift, $machine_data, $app1_data, $app2_data, $date, $_SESSION['user_id']);
if (is_string($record_id)) {
    jsAlertRedirect($record_id, $redirect);
    exit;
}
if (!$record_id) fail("Failed to create record.");

// Submit applicator1 output
$app1_status = submitApplicatorOutput($app1_data, $app1_out, $record_id);
if (is_string($app1_status)) {
    jsAlertRedirect($app1_status, $redirect);
    exit;
}

// Submit applicator2 output
$app2_status = submitApplicatorOutput($app2_data, $app2_out, $record_id);
if (is_string($app2_status)) {
    jsAlertRedirect($app2_status, $redirect);
    exit;
}

// Submit machine output
$machine_status = submitMachineOutput($machine_data, $machine_out, $record_id);
if (is_string($machine_status)) {
    jsAlertRedirect($machine_status, $redirect);
    exit;
}

// Upodate monitoring tables
require_once '../models/update_monitor_applicator.php';
$app1_monitor_status = monitorApplicatorOutput($app1_data, $app1_out);
if (is_string($app1_monitor_status)) {
    jsAlertRedirect($app1_monitor_status, $redirect);
    exit;
}

$app2_monitor_status = null;
if ($app2_data) {
    $app2_monitor_status = monitorApplicatorOutput($app2_data, $app2_out);
    if (is_string($app2_monitor_status)) {
        jsAlertRedirect($app2_monitor_status, $redirect);
        exit;
    }
}

require_once '../models/update_monitor_machine.php';
$machine_monitor_status = monitorMachineOutput($machine_data, $machine_out);
if (is_string($machine_monitor_status)) {
    jsAlertRedirect($machine_monitor_status, $redirect);
    exit;
}

// If all operations were successful, redirect to the record output page with success message
jsAlertRedirect("All outputs recorded successfully!", $redirect);
