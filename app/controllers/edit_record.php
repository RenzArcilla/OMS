<?php
/*
    This script handles the editing logic for an existing record in the database.
    It retrieves form data, sanitizes it, and updates the database record.
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../models/create_applicator_output.php';
require_once __DIR__ . '/../models/read_machines.php';
require_once __DIR__ . '/../models/read_applicators.php';
require_once __DIR__ . '/../models/update_record.php';
require_once __DIR__ . '/../models/update_applicator_output.php';
require_once __DIR__ . '/../models/update_machine_output.php';
require_once __DIR__ . '/../models/update_monitor_applicator.php';
require_once __DIR__ . '/../models/update_monitor_machine.php';
require_once __DIR__ . '/../models/delete_applicator_output.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/record_output.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. SANITATION
$record_id = isset($_POST['record_id']) ? intval($_POST['record_id']) : null;
$date_inspected = isset($_POST['date_inspected']) ? strtoupper(trim($_POST['date_inspected'])) : null;
$shift = isset($_POST['shift']) ? (['1ST' => 'FIRST', '2ND' => 'SECOND', 'NIGHT' => 'NIGHT'][strtoupper(trim($_POST['shift']))] ?? null) : null;

// Previous values for change detection
$prev_date_inspected = isset($_POST['prev_date_inspected']) ? strtoupper(trim($_POST['prev_date_inspected'])) : null;
$prev_shift = isset($_POST['prev_shift']) ? (['1ST' => 'FIRST', '2ND' => 'SECOND', 'NIGHT' => 'NIGHT'][strtoupper(trim($_POST['prev_shift']))] ?? null) : null;
$prev_app1 = isset($_POST['prev_app1']) ? strtoupper(trim($_POST['prev_app1'])) : null;
$prev_app1_output = isset($_POST['prev_app1_output']) ? intval(trim($_POST['prev_app1_output'])) : null;
$prev_app2 = isset($_POST['prev_app2']) ? strtoupper(trim($_POST['prev_app2'])) : null;
$prev_app2_output = isset($_POST['prev_app2_output']) ? intval(trim($_POST['prev_app2_output'])) : null;
$prev_machine = isset($_POST['prev_machine']) ? strtoupper(trim($_POST['prev_machine'])) : null;
$prev_machine_output = isset($_POST['prev_machine_output']) ? intval(trim($_POST['prev_machine_output'])) : null;

$app1 = isset($_POST['app1']) ? strtoupper(trim($_POST['app1'])) : null;
$app1_output = isset($_POST['app1_output']) ? intval(trim($_POST['app1_output'])) : null;
$app2 = (isset($_POST['app2']) && trim($_POST['app2']) !== '') 
    ? strtoupper(trim($_POST['app2'])) 
    : null;
$app2_output = isset($_POST['app2_output']) ? intval(trim($_POST['app2_output'])) : null;
$machine = isset($_POST['machine']) ? strtoupper(trim($_POST['machine'])) : null;
$machine_output = isset($_POST['machine_output']) ? intval(trim($_POST['machine_output'])) : null;

error_log($app2);

// 2. COMPREHENSIVE VALIDATION

/**
 * Validate required fields are not empty
 */
function validateRequiredFields($record_id, $date_inspected, $shift, $app1, $app1_output, $machine, $machine_output) {
    $required_fields = [
        'Record ID' => $record_id,
        'Date Inspected' => $date_inspected,
        'Shift' => $shift,
        'Applicator 1' => $app1,
        'Applicator 1 Output' => $app1_output,
        'Machine' => $machine,
        'Machine Output' => $machine_output
    ];
    
    $empty_fields = [];
    foreach ($required_fields as $field_name => $value) {
        if (empty($value) || (is_string($value) && trim($value) === '')) {
            $empty_fields[] = $field_name;
        }
    }
    
    if (!empty($empty_fields)) {
        throw new Exception("Please fill in all required fields: " . implode(', ', $empty_fields));
    }
}

/**
 * Validate shift value
 */
function validateShift($shift) {
    $valid_shifts = ['FIRST', 'SECOND', 'NIGHT'];
    if (!in_array($shift, $valid_shifts)) {
        throw new Exception("Invalid selection for work shift. Valid options: " . implode(', ', $valid_shifts));
    }
}

/**
 * Validate App2 conditional logic
 */
function validateApp2Logic($app2, $app2_output) {
    if (!empty($app2) && empty($app2_output)) {
        throw new Exception("Please provide output value for Applicator 2.");
    }
}

/**
 * Validate no duplicate applicators
 */
function validateNoDuplicateApplicators($app1, $app2) {
    if (!empty($app1) && !empty($app2) && $app1 === $app2) {
        throw new Exception("Error! Duplicate applicator entry: $app1");
    }
}

/**
 * Validate date format
 */
function validateDateFormat($date_inspected) {
    $date = DateTime::createFromFormat('Y-m-d', $date_inspected);
    if (!$date || $date->format('Y-m-d') !== $date_inspected) {
        throw new Exception("Invalid date format. Please use YYYY-MM-DD format.");
    }
    
    // Check if date is not in the future
    $today = new DateTime();
    if ($date > $today) {
        throw new Exception("Date inspected cannot be in the future.");
    }
}

/**
 * Validate numeric outputs are positive
 */
function validateOutputValues($app1_output, $app2_output, $machine_output) {
    if ($app1_output < 0) {
        throw new Exception("Applicator 1 output must be a positive number.");
    }
    
    if (!empty($app2_output) && $app2_output < 0) {
        throw new Exception("Applicator 2 output must be a positive number.");
    }
    
    if ($machine_output < 0) {
        throw new Exception("Machine output must be a positive number.");
    }
}

/**
 * Check if any changes were made
 */
function hasChanges($current, $previous) {
    // Check each field individually for better debugging
    $date_changed = $current['date_inspected'] !== $previous['date_inspected'];
    $shift_changed = $current['shift'] !== $previous['shift'];
    $app1_changed = $current['app1'] !== $previous['app1'];
    $app1_output_changed = intval($current['app1_output']) !== intval($previous['app1_output']);
    $app2_changed = $current['app2'] !== $previous['app2'];
    $app2_output_changed = intval($current['app2_output']) !== intval($previous['app2_output']);
    $machine_changed = $current['machine'] !== $previous['machine'];
    $machine_output_changed = intval($current['machine_output']) !== intval($previous['machine_output']);
    
    $changes_detected = (
        $date_changed ||
        $shift_changed ||
        $app1_changed ||
        $app1_output_changed ||
        $app2_changed ||
        $app2_output_changed ||
        $machine_changed ||
        $machine_output_changed
    );
    
    return $changes_detected;
}

// 2. BASIC VALIDATION FIRST
try {
    // 1. Check for required fields
    validateRequiredFields($record_id, $date_inspected, $shift, $app1, $app1_output, $machine, $machine_output);
    
    // 2. Validate date format
    validateDateFormat($date_inspected);
    
    // 3. Validate shift
    validateShift($shift);
    
    // 4. Validate app2 logic
    validateApp2Logic($app2, $app2_output);
    
    // 5. Validate no duplicate applicators
    validateNoDuplicateApplicators($app1, $app2);
    
    // 6. Validate output values are positive
    validateOutputValues($app1_output, $app2_output, $machine_output);
    
} catch (Exception $e) {
    jsAlertRedirect($e->getMessage(), $redirect_url);
    exit;
}

// 3. PREPARE DATA FOR COMPARISON AFTER SANITIZATION
$current_data = [
    'date_inspected' => $date_inspected,
    'shift' => $shift,
    'app1' => strtoupper($app1),
    'app1_output' => $app1_output,
    'app2' => strtoupper($app2 ?? ''),
    'app2_output' => $app2_output ?? 0,
    'machine' => strtoupper($machine),
    'machine_output' => $machine_output
];

$previous_data = [
    'date_inspected' => $prev_date_inspected,
    'shift' => $prev_shift,
    'app1' => strtoupper($prev_app1 ?? ''),
    'app1_output' => $prev_app1_output ?? 0,
    'app2' => strtoupper($prev_app2 ?? ''),
    'app2_output' => $prev_app2_output ?? 0,
    'machine' => strtoupper($prev_machine ?? ''),
    'machine_output' => $prev_machine_output ?? 0
];

// 4. CHECK FOR CHANGES BEFORE DATABASE OPERATIONS
if (!hasChanges($current_data, $previous_data)) {
    jsAlertRedirect('No changes detected. Record remains unchanged.', $redirect_url);
    exit;
}

// 5. DATABASE EXISTENCE AND STATUS CHECKS

// a. Check if applicators exists
$app1_data = applicatorExists($app1);
if (!is_array($app1_data)) {
    jsAlertRedirect("Applicator: $app1 not found!", $redirect_url);
    exit;
} elseif (is_string($app1_data)) {
    jsAlertRedirect($app1_data, $redirect_url);
    exit;
}

if (!empty($app2)) {
    $app2_data = applicatorExists($app2);
    if (!is_array($app2_data)) {
        jsAlertRedirect("Applicator: $app2 not found!", $redirect_url);
        exit;
    } elseif (is_string($app2_data)) {
        jsAlertRedirect($app2_data, $redirect_url);
        exit;
    }
}

// for previous applicators (will be used to differentiate app1 and app2 in the applicator_outputs table)
$prev_app1_data = applicatorExists($prev_app1);
if (!is_array($prev_app1_data)) {
    jsAlertRedirect("Previous Applicator 1: $prev_app1 not found!", $redirect_url);
    exit;
} elseif (is_string($prev_app1_data)) {
    jsAlertRedirect($prev_app1_data, $redirect_url);
    exit;
}

$prev_app2_data = null;
if (!empty($prev_app2)) {
    $prev_app2_data = applicatorExists($prev_app2);
    if (!is_array($prev_app2_data)) {
        jsAlertRedirect("Previous Applicator 2: $prev_app2 not found!", $redirect_url);
        exit;
    } elseif (is_string($prev_app2_data)) {
        jsAlertRedirect($prev_app2_data, $redirect_url);
        exit;
    }
}

// b. Check if machine exists
$machine_data = machineExists($machine);
if (!is_array($machine_data)) {
    jsAlertRedirect("Machine: $machine not found!", $redirect_url);
    exit;
} elseif (is_string($machine_data)) {
    jsAlertRedirect($machine_data, $redirect_url);
    exit;
}

// for previous machine
$prev_machine_data = machineExists($prev_machine);
if (!is_array($prev_machine_data)) {
    jsAlertRedirect("Machine: $prev_machine not found!", $redirect_url);
    exit;
} elseif (is_string($prev_machine_data)) {
    jsAlertRedirect($prev_machine_data, $redirect_url);
    exit;
}

// c. Check if applicators are disabled
$app1_disabled = getInactiveApplicatorByHpNo($app1);
if ($app1_disabled) {
    jsAlertRedirect("Applicator: $app1 is disabled!", $redirect_url);
    exit;
} elseif (is_string($app1_disabled)) {
    jsAlertRedirect($app1_disabled, $redirect_url);
    exit;
}

if (!empty($app2)) {
    $app2_disabled = getInactiveApplicatorByHpNo($app2);
    if ($app2_disabled) {
        jsAlertRedirect("Applicator: $app2 is disabled!", $redirect_url);
        exit;
    } elseif (is_string($app2_disabled)) {
        jsAlertRedirect($app2_disabled, $redirect_url);
        exit;
    }
}

// d. Check if machine is disabled
$machine_disabled = getInactiveMachineByControlNo($machine);
if ($machine_disabled) {
    jsAlertRedirect("Machine: $machine is disabled!", $redirect_url);
    exit;
} elseif (is_string($machine_disabled)) {
    jsAlertRedirect($machine_disabled, $redirect_url);
    exit;
}

// 6. DATABASE OPERATIONS
try {
    $pdo->beginTransaction();

    // e. Update the records table 
    $update_record_result = updateRecord(
        $record_id, 
        $date_inspected, 
        $shift, 
        $app1_data['applicator_id'], 
        !empty($app2) ? $app2_data['applicator_id'] : null, 
        $machine_data['machine_id']
    );
    if (is_string($update_record_result)) {
        throw new Exception($update_record_result);
    }

    // Logic for editing applicators
    include_once 'edit_records/applicator_records.php';
    // Logic for editing machines
    include_once 'edit_records/machine_records.php';

    $pdo->commit();
    jsAlertRedirect("Record updated successfully!", $redirect_url);
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsAlertRedirect("Update failed: " . $e->getMessage(), $redirect_url);
    exit;
}