<?php
/*
    This script handles the addition of a new applicator to the database.
    It retrieves form data, sanitizes it, and inserts it into the database.
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
include_once '../models/create_applicator.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", "../views/add_entry.php");
    exit;
}

// 1. Sanitize input
$control_no = isset($_POST['control_no']) ? strtoupper(trim($_POST['control_no'])) : null;
$terminal_no = isset($_POST['terminal_no']) ? strtoupper(trim($_POST['terminal_no'])) : null;
$description = isset($_POST['description']) ? strtoupper(trim($_POST['description'])) : null;
$wire_type = isset($_POST['wire_type']) ? strtoupper(trim($_POST['wire_type'])) : null;
$terminal_maker = isset($_POST['terminal_maker']) ? strtoupper(trim($_POST['terminal_maker'])) : null;
$applicator_maker = isset($_POST['applicator_maker']) ? strtoupper(trim($_POST['applicator_maker'])) : null;
$serial_no = empty($_POST['serial_no']) ? 'NO RECORD' : strtoupper(trim($_POST['serial_no']));
$invoice_no = empty($_POST['invoice_no']) ? 'NO RECORD' : strtoupper(trim($_POST['invoice_no']));

// 2. Validation
if (empty($control_no) || empty($terminal_no) || empty($description) || 
    empty($wire_type) || empty($terminal_maker) || empty($applicator_maker)) {
    jsAlertRedirect("Please fill in all required fields.", "../views/add_entry.php");
    exit;
}

if ($description !== 'SIDE' && $description !== 'END') {
    jsAlertRedirect("Invalid selection for description.", "../views/add_entry.php");
    exit;
}

if ($wire_type !== 'BIG' && $wire_type !== 'SMALL') {
    jsAlertRedirect("Invalid selection for wire type.", "../views/add_entry.php");
    exit;
} 

// 3. Database operation
$result = createApplicator($control_no, $terminal_no, $description, 
                            $wire_type, $terminal_maker, $applicator_maker, 
                            $serial_no, $invoice_no);

// Check if applicator creation was successful
if ($result === true) {
    jsAlertRedirect("Applicator added successfully!", "../views/add_entry.php");
    exit;
} elseif (is_string($result)) {
    jsAlertRedirect($result, "../views/add_entry.php");
    exit;
} else {
    jsAlertRedirect("Failed to add applicator. Please try again.", "../views/add_entry.php");
    exit;
}
