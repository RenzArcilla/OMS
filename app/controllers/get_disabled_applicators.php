<?php
/*
    Controller file for handling disabled-applicator pagination requests.
    It retrieves query parameters from the frontend (page, items_per_page, search, filters),
    calls the model function to fetch the paginated disabled-applicators,
    and returns the results in JSON format with pagination metadata.
*/

require_once __DIR__ . '/../models/read_applicators.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Get pagination parameters
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $items_per_page = isset($_GET['items_per_page']) ? max(5, min(50, (int)$_GET['items_per_page'])) : 10;
    $offset = ($current_page - 1) * $items_per_page;

    // Get and sanitize search input
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search === '') $search = null; // Treat empty string as null

    // Validate description filter
    $description = isset($_GET['description']) ? strtoupper(trim($_GET['description'])) : 'ALL';
    $allowedDescriptions = ['ALL', 'SIDE', 'END', 'CLAMP', 'STRIP AND CRIMP'];
    if (!in_array($description, $allowedDescriptions, true)) {
        $description = 'ALL'; // Default to ALL if invalid
    }

    // Validate wire type filter
    $type = isset($_GET['type']) ? strtoupper(trim($_GET['type'])) : 'ALL';
    $allowedType = ['ALL', 'BIG', 'SMALL'];
    if (!in_array($type, $allowedType, true)) {
        $type = 'ALL'; // Default to ALL if invalid
    }

    // Get total count for pagination
    $total_records = getDisabledApplicatorsCount($search, $description, $type);
    $total_pages = ceil($total_records / $items_per_page);

    // Fetch paginated applicators from the model
    $applicators = getFilteredApplicators($items_per_page, $offset, $search, $description, $type, 0);

    // Calculate pagination info
    $showing_from = $offset + 1;
    $showing_to = min($offset + $items_per_page, $total_records);

    // Successful response with data and pagination metadata
    echo json_encode([
        'success' => true,
        'data' => $applicators,
        'pagination' => [
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'items_per_page' => $items_per_page,
            'showing_from' => $showing_from,
            'showing_to' => $showing_to
        ],
        'empty_db' => empty($applicators)
    ]);

} catch (Exception $e) {
    // Failure response with error message
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
