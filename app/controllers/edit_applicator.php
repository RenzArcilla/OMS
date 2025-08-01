<?php
/*
    This script handles the editing logic for an existing applicator in the database.
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
include_once '../models/update_applicator.php';
include_once '../models/read_applicators.php';

// Redirect url
$redirect_url = "../views/add_entry.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$applicator_id = isset($_POST['applicator_id']) ? intval($_POST['applicator_id']) : null;
$hp_no = isset($_POST['control_no']) ? strtoupper(trim($_POST['control_no'])) : null;
$terminal_no = isset($_POST['terminal_no']) ? strtoupper(trim($_POST['terminal_no'])) : null;
$description = isset($_POST['description']) ? strtoupper(trim($_POST['description'])) : null;
$wire_type = isset($_POST['wire_type']) ? strtoupper(trim($_POST['wire_type'])) : null;
$terminal_maker = isset($_POST['terminal_maker']) ? strtoupper(trim($_POST['terminal_maker'])) : null;
$applicator_maker = isset($_POST['applicator_maker']) ? strtoupper(trim($_POST['applicator_maker'])) : null;
$serial_no = empty($_POST['serial_no']) ? 'NO RECORD' : strtoupper(trim($_POST['serial_no']));
$invoice_no = empty($_POST['invoice_no']) ? 'NO RECORD' : strtoupper(trim($_POST['invoice_no']));

// 2. Validation
if (empty($applicator_id) || empty($hp_no) || empty($terminal_no) || empty($description) || 
    empty($wire_type) || empty($terminal_maker) || empty($applicator_maker)) {
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
}

if ($description !== 'SIDE' && $description !== 'END') {
    jsAlertRedirect("Invalid selection for description.", $redirect_url);
    exit;
}

if ($wire_type !== 'BIG' && $wire_type !== 'SMALL') {
    jsAlertRedirect("Invalid selection for wire type.", $redirect_url);
    exit;
} 

// 3. Database operation
// Check if the applicator with the same hp_no exists and is active
$active_duplicate = getActiveApplicatorByHpNo($hp_no);
if ($active_duplicate && $active_duplicate['applicator_id'] != $applicator_id) {
    jsAlertRedirect("An applicator with this hp_no already exists.", $redirect_url);
    exit;
}

// Check if the applicator with the same hp_no exists and is inactive
$inactive_duplicate = getInactiveApplicatorByHpNo($hp_no);
if ($inactive_duplicate) {
    jsAlertRedirect("A disabled applicator with this hp_no already exists.", $redirect_url);
    exit;
}

$result = updateApplicator($applicator_id, $hp_no, $terminal_no, $description, 
                            $wire_type, $terminal_maker, $applicator_maker, 
                            $serial_no, $invoice_no);

// Check if applicator update was successful
if ($result === true) {
    jsAlertRedirect("Applicator updated successfully!", $redirect_url);
    exit;
} elseif (is_string($result)) {
    jsAlertRedirect($result, $redirect_url);
    exit;
} else {
    jsAlertRedirect("Failed to update applicator. Please try again.", $redirect_url);
    exit;
}
