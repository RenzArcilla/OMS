<?php
/*
    This file is part of the infinite-scroll logic for the machines.
    Responds with JSON containing a paginated list of machines.
*/

header('Content-Type: application/json'); // Tell the browser this returns JSON

require_once __DIR__ . '/../../app/includes/db.php';
require_once __DIR__ . '/../../app/models/read_machines.php';

// Get offset and limit from query string, with default values
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

try {
    // Fetch machine data using model function
    $machines = getMachines($pdo, $limit, $offset);

    // Return the data as JSON
    echo json_encode($machines);
} catch (PDOException $e) {
    // Handle database error with proper response
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
