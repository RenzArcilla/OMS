<?php
/*
    Controller file for handling disabled-records pagination requests.
    It retrieves query parameters from the frontend (page, items_per_page, search, filters),
    calls the model function to fetch the paginated disabled-records,
    and returns the results in JSON format with pagination metadata.
*/

require_once __DIR__ . '/../models/read_joins/record_and_outputs.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Get pagination parameters
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $items_per_page = isset($_GET['items_per_page']) ? max(5, min(50, (int)$_GET['items_per_page'])) : 10;
    $offset = ($current_page - 1) * $items_per_page;

    // Get and sanitize search input
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search === '') $search = null; // Treat empty string as null

    // Validate shift filter
    $shift = isset($_GET['shift']) ? trim($_GET['shift']) : 'ALL';
    $allowedShifts = ['ALL', '1st', '2nd', 'NIGHT'];
    if (!in_array($shift, $allowedShifts, true)) {
        $shift = 'ALL'; // Default to ALL if invalid
    }

    // Get total count for pagination
    $total_records = getDisabledRecordsCount($search, $shift);
    $total_pages = ceil($total_records / $items_per_page);

    // Fetch paginated records from the model
    $records = getFilteredRecords($items_per_page, $offset, $search, $shift, 0);

    // Calculate pagination info
    $showing_from = $offset + 1;
    $showing_to = min($offset + $items_per_page, $total_records);

    // Successful response with data and pagination metadata
    echo json_encode([
        'success' => true,
        'data' => $records,
        'pagination' => [
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'items_per_page' => $items_per_page,
            'showing_from' => $showing_from,
            'showing_to' => $showing_to
        ],
        'empty_db' => empty($records)
    ]);

} catch (Exception $e) {
    // Failure response with error message
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
