<?php
/*
    This file is the controller file for setting the output of an applicator part to zero (0).
    It retrieves form data, sanitizes it, and updates the database record.
*/


session_start();

require_once '../includes/js_alert.php';
require_once '../includes/db.php';
require_once '../models/read_monitor_applicator.php';
require_once '../models/create_applicator_reset.php';
require_once '../models/update_monitor_applicator.php';

// Redirect url
$redirect_url = "../views/dashboard_applicator.php";

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method!", $redirect_url);
    exit;
}

// Get the form data
$part_name = isset($_POST['part_name']) ? trim($_POST['part_name']) : null;
$applicator_id = isset($_POST['applicator_id']) ? trim($_POST['applicator_id']) : null;

// Check if fields are empty
if (empty($part_name) || empty($applicator_id)) {
    jsAlertRedirect("Missing required fields.", $redirect_url);
    exit;
}

// Get other needed arguments
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    jsAlertRedirect("Session expired. Please log in again.", "../views/login.php");
    exit;
}
$previous_value = getMonitorApplicatorPartOutput($applicator_id, $part_name);
if (is_string($previous_value)) {
    jsAlertRedirect($previous_value, $redirect_url);
    exit;
}

try {
    $pdo->beginTransaction();

    $result = createApplicatorReset($user_id, $applicator_id, $part_name, $previous_value);
    if (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    } 

    $result = resetApplicatorPartOutput($applicator_id, $part_name);
    if ($result === true) {
        $pdo->commit();
        jsAlertRedirect("Part output reset successful!", $redirect_url . "?filter_by=last_updated");
        exit;
    } elseif (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    } else {
        $pdo->rollBack();
        jsAlertRedirect("Failed to update applicator. Please try again.", $redirect_url);
        exit;
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    jsAlertRedirect("Database transaction failed: " . htmlspecialchars($e->getMessage()), $redirect_url);
    exit;
}
