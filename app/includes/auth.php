<?php
session_start();

// Utility: check if a user is logged in
function isAuthenticated(): bool {
    return isset($_SESSION['user_type']);
}

// Utility: get current user role (defaults to 'DEFAULT')
function getUserRole(): string {
    return $_SESSION['user_type'] ?? "DEFAULT";
}

// Redirect helper
function denyAccess($message = "You do not have the right permissions.") {
    require_once __DIR__ . '/js_alert.php';
    jsAlertRedirect($message, "../views/login.php");
    exit;
}

/**
 * Permission checks
 * (Least privilege: higher roles inherit lower roles implicitly)
 */

// DEFAULT – can only view
function requireDefault() {
    if (!isAuthenticated()) {
        denyAccess("Please log in to access this page.");
    }
}

// TOOLKEEPER – default + edit/add machine/applicator/record, view disabled, upload
function requireToolkeeper() {
    if (!isAuthenticated() || !in_array(getUserRole(), ["TOOLKEEPER", "ADMIN"])) {
        denyAccess("Toolkeeper or Admin privileges required.");
    }
}

// ADMIN – toolkeeper + restore + manage users
function requireAdmin() {
    if (!isAuthenticated() || getUserRole() !== "ADMIN") {
        denyAccess("Admin privileges required.");
    }
}
