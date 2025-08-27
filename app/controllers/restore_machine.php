<?php
/*
    This file is the controller file for restoring a disabled machine.
    It retrieves form data, sanitizes it, and updates the database record.
*/


session_start();

require_once '../includes/js_alert.php';
require_once '../includes/db.php';
require_once '../models/update_machine.php';

// Redirect url
$redirect_url = "../views/dashboard_machine.php";

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method!", $redirect_url);
    exit;
}

// Check for credentials 
if (isset($_SESSION['user_type']) != "ADMIN") {
    jsAlertRedirect("You do not have the right permissions" . var_dump($_SESSION), "../views/login.php");
    exit;
}

// Get the form data
$machine_id = isset($_POST['machine_id']) ? trim($_POST['machine_id']) : null;

// Check if fields are empty
if (empty($machine_id)) {
    jsAlertRedirect("Missing required fields.", $redirect_url);
    exit;
}

try {
    $pdo->beginTransaction();

    $result = restoreDisabledMachine($machine_id);
    if ($result === true) {
        $pdo->commit();
        jsAlertRedirect("Machine restored successfully!", $redirect_url);
        exit;
    } elseif (is_string($result)) {
        $pdo->rollBack();
        jsAlertRedirect($result, $redirect_url);
        exit;
    } else {
        $pdo->rollBack();
        jsAlertRedirect("Failed to restore machine. Please try again.", $redirect_url);
        exit;
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    jsAlertRedirect("Database transaction failed: " . htmlspecialchars($e->getMessage()), $redirect_url);
    exit;
}
