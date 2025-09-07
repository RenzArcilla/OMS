<?php
/*
    This script handles the addition of a new applicator to the database.
    It retrieves form data, sanitizes it, and inserts it into the database.
*/


// Include necessary files
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../includes/db.php'; 
require_once __DIR__ . '/../models/create_custom_part.php';
require_once __DIR__ . '/../models/read_custom_parts.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

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
$type = isset($_POST['equipment_type']) ? strtoupper(trim($_POST['equipment_type'])) : null;
$name = isset($_POST['custom_part_name']) 
        ? strtolower(preg_replace('/\s+/', '_', trim($_POST['custom_part_name']))) 
        : null;

// 2. Validation
if (empty($type) || empty($name)) {
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
}

// Check for equipment type
$valid_types = ["APPLICATOR", "MACHINE", "PRESS"];
if (!in_array($type, $valid_types, true)) {
    jsAlertRedirect("Invalid equipment type.", $redirect_url);
    exit;
}

// Check if new part name is unique
$existing_part = getCustomPartByName($name);
if ($existing_part) {
    jsAlertRedirect("Part name already exists.", $redirect_url);
    exit;
}

// 3. Database operation
$pdo->beginTransaction();
$result = createCustomPart((int) $_SESSION['user_id'], $type, $name);

// Check if custom part creation was successful
if ($result === true) {
    $pdo->commit();
    jsAlertRedirect("Custom part added successfully!", $redirect_url);
    exit;
} elseif (is_string($result)) {
    $pdo->rollBack(); // Rollback transaction in case of error
    jsAlertRedirect($result, $redirect_url);
    exit;
} else {
    $pdo->rollBack();
    jsAlertRedirect("Failed to add custom part. Please try again.", $redirect_url);
    exit;
}
