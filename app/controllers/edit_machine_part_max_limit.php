<?php 
/*
    This controller handles updating the maximum output limit for machine parts.
    It processes POST requests containing the selected parts and the new output limit,
    and updates the database accordingly.
*/

// Include necessary files
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/js_alert.php';
require_once '../models/read_machines.php';
require_once '../models/update_machine_part_limits.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/dashboard_machine.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Retrieve and sanitize POST data
$control_number = isset($_POST['control_number']) ? strtoupper(trim($_POST['control_number'])) : null;
$output_limit = isset($_POST['output_limit']) ? (int) trim($_POST['output_limit']) : null;
$selected_parts = isset($_POST['parts']) ? $_POST['parts'] : [];

// 2. Validate required fields
if (!$control_number || !$output_limit) {
    jsAlertRedirect("Missing required fields!", $redirect_url);
    exit;
}

// 2.1 Check if at least one part is selected
if (empty($selected_parts)) {
    jsAlertRedirect("Please select at least one machine part.", $redirect_url);
    exit;
}

// 2.3 Validate output limit
if (!is_numeric($output_limit) || $output_limit <= 0) {
    jsAlertRedirect("Output limit must be a positive number.", $redirect_url);
    exit;
}

// 3. Check if machine exists
$is_inactive = getInactiveMachineByControlNo($control_number);
if ($is_inactive) {
    jsAlertRedirect("The specified machine is inactive.", $redirect_url);
    exit;
}

// 3.1 Check if machine exists and is active
$is_active = machineExists($control_number);
if (empty($is_active)) {
    jsAlertRedirect("The specified machine does not exist.", $redirect_url);
    exit;
}

// Get machine ID
$machine_id = $is_active['machine_id'];

// 4. Insert or update part limits
$pdo->beginTransaction();
foreach ($selected_parts as $part) {
    $result = updateMachinePartLimits($machine_id, $part, $output_limit);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    }
}

// Success
$pdo->commit();
jsAlertRedirect("Machine part limits updated successfully.", $redirect_url);
exit;