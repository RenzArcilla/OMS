<?php
/*
    This script handles the addition of a new machine to the database.
    It retrieves form data, sanitizes it, and inserts it into the database.
*/


// Include necessary files
require_once __DIR__ . '/../includes/db.php'; 
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../models/read_machines.php';
require_once __DIR__ . '/../models/create_machine.php';

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
$description = isset($_POST['description']) ? strtoupper(trim($_POST['description'])) : null;
$model = isset($_POST['model']) ? strtoupper(trim($_POST['model'])) : null;
$machine_maker = isset($_POST['machine_maker']) ? strtoupper(trim($_POST['machine_maker'])) : null;
$serial_no = empty($_POST['serial_no']) ? 'NO RECORD' : strtoupper(trim($_POST['serial_no']));
$invoice_no = empty($_POST['invoice_no']) ? 'NO RECORD' : strtoupper(trim($_POST['invoice_no']));


// 2. Validation
if (empty($control_no) || empty($description) || empty($model) || empty($machine_maker)) {
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
} 

if ($description !== 'AUTOMATIC' && $description !== 'SEMI-AUTOMATIC') {
    jsAlertRedirect("Invalid selection for description.", $redirect_url);
    exit;
}

// Helper: Extract multiple columns from a list of machines
function extractColumnValues($machines, $keys) {
    /*
        Extract specified columns from a list of machines.
        Returns an associative array where keys are column names and values are arrays of column values.
    */
    $columns = [];
    foreach ($keys as $key) {
        // array_column pulls out all values for a given field from the machine list
        $columns[$key] = array_column($machines, $key);
    }
    return $columns;
}

// Define which fields we want to check for duplicates
$keys = ['control_no', 'serial_no', 'invoice_no'];

// Fetch active and inactive machines
$active_machines = fetchAllMachines(1);
$inactive_machines = fetchAllMachines(0);

// Extract control_no, serial_no, invoice_no arrays for active/inactive
$active   = extractColumnValues($active_machines, $keys);
$inactive = extractColumnValues($inactive_machines, $keys);

// Loop through each field and check if the new value already exists
foreach ($keys as $key) {
    // Variable-variable syntax $$key → $control_no, $serial_no, $invoice_no
    if (in_array($$key, $active[$key])) {
        jsAlertRedirect(ucwords(str_replace('_', ' ', $key)) . " already exists for an active machine.", $redirect_url);
        exit;
    }
    if (in_array($$key, $inactive[$key])) {
        jsAlertRedirect(ucwords(str_replace('_', ' ', $key)) . " already exists for an inactive machine.", $redirect_url);
        exit;
    }
}

// 3. Database operation
$pdo->beginTransaction();
$result = createMachine($control_no, $description, $model,
                        $machine_maker, $serial_no, $invoice_no);

// Check if applicator creation was successful
if ($result === true) {
    $pdo->commit();
    jsAlertRedirect("Machine added successfully!", $redirect_url);
    exit;
} elseif (is_string($result)) {
    $pdo->rollBack(); // Rollback transaction in case of error
    jsAlertRedirect($result, $redirect_url);
    exit;
} else {
    $pdo->rollBack();
    jsAlertRedirect("Failed to add Machine. Please try again.", $redirect_url);
    exit;
}
