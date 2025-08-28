<?php
/*
    This script handles the editing logic for an existing machine in the database.
    It retrieves form data, sanitizes it, and updates the database record.
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Include necessary files
require_once '../includes/js_alert.php';
include_once '../models/update_machine.php';
include_once '../models/read_machines.php';

// Redirect url
$redirect_url = "../views/add_entry.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$machine_id = isset($_POST['machine_id']) ? intval($_POST['machine_id']) : null;
$control_no = isset($_POST['control_no']) ? strtoupper(trim($_POST['control_no'])) : null;
$description = isset($_POST['description']) ? strtoupper(trim($_POST['description'])) : null;
$model = isset($_POST['model']) ? strtoupper(trim($_POST['model'])) : null;
$machine_maker = isset($_POST['machine_maker']) ? strtoupper(trim($_POST['machine_maker'])) : null;
$serial_no = empty($_POST['serial_no']) ? 'NO RECORD' : strtoupper(trim($_POST['serial_no']));
$invoice_no = empty($_POST['invoice_no']) ? 'NO RECORD' : strtoupper(trim($_POST['invoice_no']));

// 2. Validation
if (empty($machine_id) || empty($control_no) || empty($description) ||
    empty($model) || empty($machine_maker)) {
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
}

if ($description !== 'AUTOMATIC' && $description !== 'SEMI-AUTOMATIC') {
    jsAlertRedirect("Invalid selection for description.", $redirect_url);
    exit;
}

// 3. Database operation
// Check if the machine with the same control_no exists and is active
$active_duplicate = getActiveMachineByControlNo($control_no);
if ($active_duplicate && $active_duplicate['control_no'] != $control_no) {
    jsAlertRedirect("An active machine with control number: $control_no already exists.", $redirect_url);
    exit;
}

// Check if the machine with the same control_no exists and is inactive
$inactive_duplicate = getInactiveMachineByControlNo($control_no);
if ($inactive_duplicate) {
    jsAlertRedirect("A disabled machine with control number: $control_no already exists.", $redirect_url);
    exit;
}

// Update the machine in the database
$pdo->beginTransaction();
$result = updateMachine($machine_id, $control_no, $description, $model,
                        $machine_maker, $serial_no, $invoice_no);

// Check if machine update was successful
if ($result === true) {
    $pdo->commit();
    jsAlertRedirect("machine updated successfully!", $redirect_url);
    exit;
} elseif (is_string($result)) {
    $pdo->rollBack(); // Rollback transaction in case of error
    jsAlertRedirect($result, $redirect_url);
    exit;
} else {
    $pdo->rollBack();
    jsAlertRedirect("Failed to update machine. Please try again.", $redirect_url);
    exit;
}
