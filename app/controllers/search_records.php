<?php
/*
    Controller file for handling record search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered records,
    and returns the results in JSON format.
*/

require_once __DIR__ . '/../models/read_joins/record_and_outputs.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Search
    $search = isset($_GET['q']) ? strtoupper(trim($_GET['q'])) : null;
    if ($search === '') {
        $search = null;
    }

    // Shift filter
    $shift = isset($_GET['shift']) ? trim($_GET['shift']) : 'ALL';
    $allowedShifts = ['ALL', '1st', '2nd', 'NIGHT'];
    if (!in_array($shift, $allowedShifts, true)) {
        $shift = 'ALL';
    }

    // Pagination
    $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 20;
    $offset = ($page - 1) * $limit;

    // Fetch filtered results
    $records = getFilteredRecords($limit, $offset, $search, $shift);

    // Simplest efficient check:
    // If nothing is returned AND page=1 AND no filters applied
    $emptyDb = false;
    if (empty($records) && $page === 1 && $search === null && $shift === 'ALL') {
        $emptyDb = true;
    }

    echo json_encode([
        'success' => true,
        'data' => $records,
        'empty_db' => $emptyDb
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}