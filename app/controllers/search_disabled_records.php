<?php
/*
    Controller file for handling disabled-record search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered disabled-records,
    and returns the results in JSON format.
*/

require_once __DIR__ . '/../models/read_joins/record_and_outputs.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Validate inputs
    $search = isset($_GET['q']) ? strtoupper(trim($_GET['q'])) : null;
    if ($search === '') {
        $search = null;
    }

    $shift = isset($_GET['shift']) ? trim($_GET['shift']) : 'ALL';
    $allowedShifts = ['ALL', '1st', '2nd', 'NIGHT'];
    if (!in_array($shift, $allowedShifts, true)) {
        $shift = 'ALL';
    }

    $type = isset($_GET['type']) ? trim($_GET['type']) : 'ALL';
    $allowedtype = ['ALL', 'BIG', 'SMALL']; 
    if (!in_array($type, $allowedtype, true)) {
        $type = 'ALL';
    }

    // Fetch filtered results
    $records = getDisabledRecordsAndOutputs(20, 0, $search);

    // simplest efficient check
    $emptyDb = empty($records) && $search === null && $shift === 'ALL';

    echo json_encode([
        'success' => true,
        'data' => $records,
        'empty_db' => $emptyDb
    ]);

} catch (Exception $e) {
    // Return error as JSON
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}