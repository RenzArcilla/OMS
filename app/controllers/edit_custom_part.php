<?php
/*
    This script handles the editing logic for an existing custom part in the database.
    It retrieves form data, sanitizes it, checks for duplicate part names, and updates the part record.
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php'; // Database connection
require_once __DIR__ . '/../includes/js_alert.php'; // JavaScript alert function
require_once __DIR__ . '/../models/read_custom_parts.php'; // Read custom part model
require_once __DIR__ . '/../models/update_custom_part.php'; // Update custom part model

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

// Redirect URL
$redirect_url = "../views/dashboard_applicator.php";
if (strtoupper(trim($_POST['equipment_type'])) === "MACHINE") {
    $redirect_url = "../views/dashboard_machine.php";
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 0. Initialize variables
$result = null;

// 1. Sanitize input
$part_id = isset($_POST['edit_part_id']) ? intval($_POST['edit_part_id']) : null;
$type = isset($_POST['equipment_type']) ? strtoupper(trim($_POST['equipment_type'])) : null;
$name = isset($_POST['edit_part_name']) 
        ? strtolower(preg_replace('/\s+/', '_', trim($_POST['edit_part_name']))) 
        : null;

// 2. Validation
if (empty($type) || empty($name) || empty($part_id)) {
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
}

// Check if nothing changed
if ($result && $result['part_id'] === $part_id && $result['part_name'] === $name && $result['equipment_type'] === $type) {
    jsAlertRedirect("No changes detected.", $redirect_url);
    exit;
}

// Check for equipment type
$valid_types = ["APPLICATOR", "MACHINE", "PRESS"];
if (!in_array($type, $valid_types, true)) {
    jsAlertRedirect("Invalid equipment type.", $redirect_url);
    exit;
}

$user = isset($_SESSION['user_id']);

// 3. Check for duplicate part name (excluding the current user)
$result = getCustomPartByName($name);
if (is_string($result)) {
    jsAlertRedirect($result, $redirect_url);
    exit;
}

if ($result && $result['part_id'] != $part_id) {
    jsAlertRedirect("A custom part with name '$name' already exists.", $redirect_url);
    exit;
}

// 4. Update the custom part
try {
    $pdo->beginTransaction();

    $result = updateCustomPart($part_id, $name, $type);

    if ($result === true) {
        $pdo->commit();
        jsAlertRedirect("Custom part updated successfully!", $redirect_url);
        exit;
    } else {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url); // $result is probably an error message
        exit;
    }

} catch (Exception $e) {
    // Roll back only if transaction is still active
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    jsAlertRedirect("An unexpected error occurred: " . $e->getMessage(), $redirect_url);
    exit;
}
