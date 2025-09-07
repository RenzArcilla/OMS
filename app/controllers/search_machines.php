<?php
/*
    Controller file for handling machine search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered machines,
    and returns the results in JSON format.
*/

require_once __DIR__ . '/../models/read_machines.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $search = isset($_GET['q']) ? trim($_GET['q']) : null;
    $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 20;
    $offset = ($page - 1) * $limit;

    if ($search === '') $search = null;

    $description = isset($_GET['description']) ? strtoupper(trim($_GET['description'])) : 'ALL';
    $allowedDescriptions = ['ALL', 'AUTOMATIC', 'SEMI-AUTOMATIC'];
    if (!in_array($description, $allowedDescriptions, true)) $description = 'ALL';

    // Fetch filtered results
    $machines = getFilteredMachines($limit, $offset, $search, $description);

    // Determine if DB is empty (initial fetch)
    $emptyDb = empty($machines) && $page === 1 && $search === null && $description === 'ALL';

    echo json_encode([
        'success' => true,
        'data' => $machines,
        'empty_db' => $emptyDb
    ]);

} catch (Exception $e) {
    // Return error as JSON
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}