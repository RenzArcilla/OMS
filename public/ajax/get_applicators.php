<?php
/*
    This file is part of the infinite-scroll logic for the applicators.
    Responds with JSON containing a paginated list of applicators.
*/

header('Content-Type: application/json'); // Tell the browser this returns JSON

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/READ_applicators.php';

// Get offset and limit from query string, with default values
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

try {
    // Fetch applicator data
    $applicators = getApplicators($pdo, $limit, $offset);

    // Return JSON response
    echo json_encode($applicators);
} catch (PDOException $e) {
    // Handle any DB errors gracefully
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
