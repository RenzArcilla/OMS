<?php 
/*
    This controller handles updating the maximum output limit for applicator parts.
    It processes POST requests containing the selected parts and the new output limit,
    and updates the database accordingly.
*/

// Include necessary files
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../models/read_applicators.php';
require_once __DIR__ . '/../models/update_applicator_part_limits.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/dashboard_applicator.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Retrieve and sanitize POST data
$hp_number = isset($_POST['hp_number']) ? strtoupper(trim($_POST['hp_number'])) : null;
$output_limit = isset($_POST['output_limit']) ? (int) trim($_POST['output_limit']) : null;
$selected_parts = isset($_POST['parts']) ? $_POST['parts'] : [];

// 2. Validate required fields
if (!$hp_number || !$output_limit) {
    jsAlertRedirect("Missing required fields!", $redirect_url);
    exit;
}

// 2.1 Check if at least one part is selected
if (empty($selected_parts)) {
    jsAlertRedirect("Please select at least one applicator part.", $redirect_url);
    exit;
}

// 2.3 Validate output limit
if (!is_numeric($output_limit) || $output_limit <= 0) {
    jsAlertRedirect("Output limit must be a positive number.", $redirect_url);
    exit;
}

// 3. Check if applicator exists
$is_inactive = getInactiveApplicatorByHpNo($hp_number);
if ($is_inactive) {
    jsAlertRedirect("The specified applicator is inactive.", $redirect_url);
    exit;
}

// 3.1 Check if applicator exists and is active
$is_active = applicatorExists($hp_number);
if (empty($is_active)) {
    jsAlertRedirect("The specified applicator does not exist.", $redirect_url);
    exit;
}

// Get applicator ID
$applicator_id = $is_active['applicator_id'];

// 4. Insert or update part limits
$pdo->beginTransaction();
foreach ($selected_parts as $part) {
    $result = updateApplicatorPartLimits($applicator_id, $part, $output_limit);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    }
}

// Success
$pdo->commit();
jsAlertRedirect("Applicator part limits updated successfully.", $redirect_url);
exit;