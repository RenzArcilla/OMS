<?php
/*
    This script serves as the controller that handles the export logic for applicator outputs to excel.
    It retrieves form data, sanitizes it, and updates the database record.
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../includes/export_helpers/export_applicator_output_helper.php';
require_once __DIR__ . '/../models/read_joins/read_monitor_applicator_and_applicator.php';
require_once __DIR__ . '/../models/read_custom_parts.php';


// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/dashboard_applicator.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$include_headers = isset($_POST['includeHeaders']) ? 1 : 0;

// 2. Validation
if (empty($include_headers)) {
    jsAlertRedirect("Please specify whether to include headers.", $redirect_url);
    exit;
}

// Pass data into export helper
exportApplicatorOutputToExcel($include_headers);
exit;