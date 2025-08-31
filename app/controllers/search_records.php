<?php
/*
    Controller file for handling record search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered records,
    and returns the results in JSON format.
*/

require_once "../models/read_joins/record_and_outputs.php";

header('Content-Type: application/json; charset=utf-8');

try {
    // Validate inputs
    $search = isset($_GET['q']) ? trim($_GET['q']) : null;
    if ($search === '') {
        $search = null;
    }

    $shift = isset($_GET['shift']) ? strtoupper(trim($_GET['shift'])) : 'ALL';
    $allowedShifts = ['ALL', '1st', '2nd', 'NIGHT'];
    if (!in_array($shift, $allowedShifts, true)) {
        $shift = 'ALL';
    }

    $type = isset($_GET['type']) ? strtoupper(trim($_GET['type'])) : 'ALL';
    $allowedtype = ['ALL', 'BIG', 'SMALL']; 
    if (!in_array($type, $allowedtype, true)) {
        $type = 'ALL';
    }

    // Fetch filtered results
    $records = getFilteredrecords(20, 0, $search, $shift);

    echo json_encode([
        'success' => true,
        'data' => $records
    ]);

} catch (Exception $e) {
    // Return error as JSON
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}