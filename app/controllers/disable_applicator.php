<?php
/*
    This script handles the disabling (soft delete) of an applicator to the database.
    It retrieves form data, sanitizes it, and updates the status in the database.
    - disables applicator
    - disables outputs of the applicator
    - disable cumulative outputs of the applicator
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../models/delete_applicator.php';
require_once __DIR__ . '/../models/update_applicator_output.php';
require_once __DIR__ . '/../models/update_monitor_applicator.php';

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
$applicator_id = isset($_POST['applicator_id']) ? strtoupper(trim($_POST['applicator_id'])) : null;

// 2. Validation
if (empty($applicator_id)) {
    jsAlertRedirect("applicator ID is required.", $redirect_url);
    exit;
}

// 3. Database operation
try {
    $pdo->beginTransaction();

    // disables applicator
    $disableApplicator = disableApplicator($applicator_id);
    if (is_string($disableApplicator)) {
        throw new Exception($disableApplicator);
    }

    // disables outputs of the applicator
    $disableOutputs = disableApplicatorOutputs($applicator_id);
    if (is_string($disableOutputs)) {
        throw new Exception($disableOutputs);
    }

    // disable cumulative outputs of the applicator
    $disableCumulativeOutputs = disableApplicatorCumulativeOutputs($applicator_id);
    if (is_string($disableCumulativeOutputs)) {
        throw new Exception($disableCumulativeOutputs);
    }

    // Check if all returned true
    if ($disableApplicator && $disableOutputs && $disableCumulativeOutputs) {
        $pdo->commit();
        jsAlertRedirect("Applicator disabled successfully!", $redirect_url);
        exit;
    } else {
        throw new Exception("Failed to disable applicator. Please try again.");
    }

} catch (Exception $e) {
    // Rollback on any error
    $pdo->rollBack();
    jsAlertRedirect($e->getMessage(), $redirect_url);
    exit;
}
