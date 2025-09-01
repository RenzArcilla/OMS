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
    // Validate inputs
    $search = isset($_GET['q']) ? trim($_GET['q']) : null;
    if ($search === '') {
        $search = null;
    }

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

    // Fetch filtered results
    $applicators = getFilteredApplicators(20, 0, $search, $description, $type, 0);

    echo json_encode([
        'success' => true,
        'data' => $applicators
    ]);

} catch (Exception $e) {
    // Return error as JSON
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}