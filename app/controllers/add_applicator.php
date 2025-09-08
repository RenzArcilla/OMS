<?php
/*
    This script handles the addition of a new applicator to the database.
    It retrieves form data, sanitizes it, and inserts it into the database.
*/


// Include necessary files
require_once __DIR__ . '/../includes/db.php'; 
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../models/create_applicator.php';
require_once __DIR__ . '/../models/read_applicators.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/add_entry.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
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
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
}

if ($description !== 'SIDE' && $description !== 'END'  && $description !== 'CLAMP'  
    && $description !== 'STRIP AND CRIMP' ) {
    jsAlertRedirect("Invalid selection for description.", $redirect_url);
    exit;
}

if ($wire_type !== 'BIG' && $wire_type !== 'SMALL') {
    jsAlertRedirect("Invalid selection for wire type.", $redirect_url);
    exit;
} 

// Helper: Extract multiple columns from a list of applicators
function extractColumnValues($applicators, $keys) {
    /*
        Extract specified columns from a list of applicators.
        Returns an associative array where keys are column names and values are arrays of column values.
    */
    $columns = [];
    foreach ($keys as $key) {
        // array_column pulls out all values for a given field from the applicator list
        $columns[$key] = array_column($applicators, $key);
    }
    return $columns;
}

// Alias for clarity in duplicate checks
$hp_no = $control_no;

// Define which fields we want to check for duplicates
$keys = ['hp_no', 'terminal_no', 'serial_no', 'invoice_no'];

// Fetch active and inactive applicators
$active_applicators = fetchAllApplicators(1);
$inactive_applicators = fetchAllApplicators(0);

// Extract hp_no, terminal_no, serial_no, invoice_no arrays for active/inactive
$active   = extractColumnValues($active_applicators, $keys);
$inactive = extractColumnValues($inactive_applicators, $keys);

// Loop through each field and check if the new value already exists
foreach ($keys as $key) {
    // Variable-variable syntax $$key → $hp_no, $terminal_no, $serial_no, $invoice_no
    if (in_array($$key, $active[$key])) {
        jsAlertRedirect(ucwords(str_replace('_', ' ', $key)) . " already exists for an active applicator.", $redirect_url);
        exit;
    }
    if (in_array($$key, $inactive[$key])) {
        jsAlertRedirect(ucwords(str_replace('_', ' ', $key)) . " already exists for an inactive applicator.", $redirect_url);
        exit;
    }
}


// 3. Database operation
$pdo->beginTransaction();
$result = createApplicator($control_no, $terminal_no, $description, 
                            $wire_type, $terminal_maker, $applicator_maker, 
                            $serial_no, $invoice_no);

// Check if applicator creation was successful
if ($result === true) {
    $pdo->commit();
    jsAlertRedirect("Applicator added successfully!", $redirect_url);
    exit;
} elseif (is_string($result)) {
    $pdo->rollBack(); // Rollback transaction in case of error
    jsAlertRedirect($result, $redirect_url);
    exit;
} else {
    $pdo->rollBack();
    jsAlertRedirect("Failed to add applicator. Please try again.", $redirect_url);
    exit;
}
