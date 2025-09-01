<?php
/*
    This script handles the export logic for records within the database.
    It retrieves form data, sanitizes it, and updates the database record.
*/


// Include necessary files
require_once '../includes/auth.php';
require_once '../includes/js_alert.php';
require_once '../includes/export_helpers/export_record_helper.php';
require_once '../models/update_applicator.php';
require_once '../models/read_applicators.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect url
$redirect_url = "../views/record_output.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$date_range      = isset($_POST['dateRange']) ? htmlspecialchars(trim($_POST['dateRange'])) : 'all';
$include_headers = isset($_POST['includeHeaders']) ? 1 : 0;
$start_date      = isset($_POST['startDate']) ? htmlspecialchars(trim($_POST['startDate'])) : null;
$end_date        = isset($_POST['endDate']) ? htmlspecialchars(trim($_POST['endDate'])) : null;

// 2. Validation
// Validate required fields
if (empty($date_range)) {
    jsAlertRedirect("Please select a date range.", $redirect_url);
} 
if ($date_range === 'custom' && (empty($start_date) || empty($end_date))) {
    jsAlertRedirect("Please provide both start and end dates for custom range.", $redirect_url);
}

// Validate date range
$valid_ranges = ['all', 'today', 'week', 'month', 'quarter', 'year', 'custom'];
if (!in_array($date_range, $valid_ranges)) {
    jsAlertRedirect("Invalid date range option.", $redirect_url);
}

// If custom date range, validate both start and end
if ($date_range === 'custom') {
    if (empty($start_date) || empty($end_date)) {
        jsAlertRedirect("Both start and end dates are required for custom range.", $redirect_url);
    } elseif (strtotime($start_date) > strtotime($end_date)) {
        jsAlertRedirect("Start date cannot be later than end date.", $redirect_url);
    }
}

// Pass data into export helper
exportRecordsToExcel($include_headers, $date_range, $start_date, $end_date);

exit;