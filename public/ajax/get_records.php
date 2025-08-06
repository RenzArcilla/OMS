<?php
/*
    This file is part of the infinite-scroll logic for the records table.
    Responds with JSON containing a paginated list of records.
*/

header('Content-Type: application/json'); // Tells the browser this returns JSON

require_once __DIR__ . '/../../app/includes/db.php';
require_once __DIR__ . '/../../app/models/read_joins/record_and_outputs.php';

// Get offset and limit from query string, with default values
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;

try {
    // Fetch applicator data
    $records = getRecordsAndOutputs($limit, $offset);

    // Return JSON response
    echo json_encode($records);
} catch (PDOException $e) {
    // Handle any DB errors gracefully
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
