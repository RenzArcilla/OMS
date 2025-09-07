<?php
/*
    Controller file for handling user search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered users,
    and returns the results in JSON format.
*/

// Include the model for fetching users
require_once __DIR__ . '/../models/read_user.php';

// Always set JSON header first
header('Content-Type: application/json; charset=utf-8');

try {
    // Get raw inputs
    $search = $_GET['search'] ?? '';
    $role   = $_GET['role'] ?? 'all';
    $page  = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10;
    $offset = ($page - 1) * $limit;

    // Sanitize search term
    $search = trim($search);

    // Whitelist role validation
    $allowedRoles = ['ADMIN', 'TOOLKEEPER', 'DEFAULT', 'all'];
    if (!in_array($role, $allowedRoles, true)) {
        $role = 'all';
    }

    // Call model
    $users = searchUsers($search, $role, $limit, $offset);
    $total = countUsers($search, $role);

    $response = [
        'data' => $users,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'total_pages' => max(1, (int)ceil($total / $limit))
        ]
    ];

    // Ensure we return valid JSON
    $json = json_encode($response);

    if ($json === false) {
        // Encoding failed — respond with error
        echo json_encode([
            'error' => 'Failed to encode users to JSON',
            'details' => json_last_error_msg()
        ]);
        exit;
    }

    // Clear any accidental output before sending JSON
    if (ob_get_length()) {
        ob_clean();
    }

    echo $json;

} catch (Throwable $e) {
    // Always return JSON, never raw PHP errors
    echo json_encode(['error' => $e->getMessage()]);
}
