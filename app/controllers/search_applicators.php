<?php
/*
    Controller file for handling applicator search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered applicators,
    and returns the results in JSON format.
*/

require_once __DIR__ . '/../models/read_applicators.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Validate inputs
    $search = isset($_GET['q']) ? trim($_GET['q']) : null;
    if ($search === '') {
        $search = null;
    }

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 20;
    $offset = ($page - 1) * $limit;

    $description = isset($_GET['description']) ? strtoupper(trim($_GET['description'])) : 'ALL';
    $allowedDescriptions = ['ALL', 'SIDE', 'END', 'CLAMP', 'STRIP AND CRIMP'];
    if (!in_array($description, $allowedDescriptions, true)) {
        $description = 'ALL';
    }

    $type = isset($_GET['type']) ? strtoupper(trim($_GET['type'])) : 'ALL';
    $allowedtype = ['ALL', 'BIG', 'SMALL'];
    if (!in_array($type, $allowedtype, true)) {
        $type = 'ALL';
    }

    // Debug: Log the request
    error_log("Search applicators request: search=$search, page=$page, limit=$limit, description=$description, type=$type");

    // Fetch filtered results with pagination
    $applicators = getFilteredApplicators($limit, $offset, $search, $description, $type);
    
    // Get total count for pagination
    $totalCount = getApplicatorsCount($search, $description, $type);
    $totalPages = ceil($totalCount / $limit);

    // Debug: Log the results
    error_log("Applicators found: " . count($applicators) . ", Total count: $totalCount");

    // Determine empty database
    $emptyDb = empty($applicators) && $page === 1 && $search === null && $description === 'ALL' && $type === 'ALL';

    echo json_encode([
        'success' => true,
        'data' => $applicators,
        'empty_db' => $emptyDb,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_count' => $totalCount,
            'limit' => $limit,
            'offset' => $offset
        ]
    ]);

} catch (Exception $e) {
    // Return error as JSON
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}