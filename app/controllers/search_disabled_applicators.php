<?php
/*
    Controller file for handling disabled-applicator search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered disabled-applicators,
    and returns the results in JSON format.
*/

require_once "../models/read_applicators.php";

header('Content-Type: application/json; charset=utf-8');

try {
    // Get and sanitize search input
    $search = isset($_GET['q']) ? trim($_GET['q']) : null;
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

    // Fetch filtered applicators from the model
    $applicators = getFilteredApplicators(20, 0, $search, $description, $type, 0);

    // Successful response with data and empty_db indicator
    echo json_encode([
        'success'   => true,
        'data'      => $applicators,
        'empty_db'  => empty($applicators)
    ]);

} catch (Exception $e) {
    // Failure response with error message
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}