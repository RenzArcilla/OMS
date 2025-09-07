<?php
/*
    This script handles the deletion of a custom part from the database.
    It retrieves the part ID, sanitizes it, and deletes the corresponding record.
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../includes/db.php'; 
require_once __DIR__ . '/../models/delete_custom_part.php';

// Require Admin Privileges
requireAdmin();

// Redirect url
$redirect_url = "../views/dashboard_applicator.php";
if (strtoupper(trim($_POST['equipment_type'])) === "MACHINE") {
    $redirect_url = "../views/dashboard_machine.php";
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}


// 1. Sanitize input
$part_id = isset($_POST['part_id']) ? intval($_POST['part_id']) : null;

// 2. Validation
if (empty($part_id)) {
    jsAlertRedirect("Invalid part ID.", $redirect_url);
    exit;
}

// 3. Database operation
$pdo->beginTransaction();
$result = deleteCustomPart($part_id);

// Check if custom part deletion was successful
if ($result === true) {
    $pdo->commit();
    jsAlertRedirect("Custom part deleted successfully!", $redirect_url);
    exit;
} elseif (is_string($result)) {
    $pdo->rollBack(); // Rollback transaction in case of error
    jsAlertRedirect($result, $redirect_url);
    exit;
} else {
    $pdo->rollBack();
    jsAlertRedirect("Failed to delete custom part. Please try again.", $redirect_url);
    exit;
}
