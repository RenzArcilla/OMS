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

// --- Helper: Fail during error ---
function fail($msg) {
    jsAlertRedirect($msg, "../views/record_output.php");
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
$app1_out = getInput('app1_output');
$app2 = getInput('app2');
$app2_out = getInput('app2_output');
$machine = getInput('machine');
$machine_out = getInput('machine_output');

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
$app1_id = applicatorExists($app1);
if (!$app1_id) fail("Applicator 1 not found.");
if (is_string($app1_id)) fail($app1_id);

$app2_id = null;
if (!empty($app2)) {
    $app2_id = applicatorExists($app2);
    if (!$app2_id) fail("Applicator 2 not found.");
    if (is_string($app2_id)) fail($app2_id);
}

$machine_id = machineExists($machine);
if (!$machine_id) fail("Machine not found.");
if (is_string($machine_id)) fail($machine_id);

// 4. Database operation
$record_id = createRecord($shift, $machine_id, $app1_id, $date, $_SESSION['user_id']);
if (!$record_id) fail("Failed to create record.");

if (!submitApplicatorOutput($app1_id, $app1_out, $record_id) ||
    ($app2_id && !submitApplicatorOutput($app2_id, $app2_out, $record_id)) ||
    !submitMachineOutput($machine_id, $machine_out, $record_id)
) {
    fail("Error recording outputs.");
}

jsAlertRedirect("All outputs recorded successfully!", "../views/record_output.php");
