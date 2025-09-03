<?php
/*
    Controller file for handling disabled-machine search requests.
    It retrieves query parameters from the frontend (search and role),
    calls the model function to fetch the filtered disabled-machines,
    and returns the results in JSON format.
*/

require_once "../models/read_machines.php";

header('Content-Type: application/json; charset=utf-8');

try {
    // Validate inputs
    $search = isset($_GET['q']) ? trim($_GET['q']) : null;
    if ($search === '') {
        $search = null;
    }

    $description = isset($_GET['description']) ? strtoupper(trim($_GET['description'])) : 'ALL';
    $allowedDescriptions = ['ALL', 'AUTOMATIC', 'SEMI-AUTOMATIC'];
    if (!in_array($description, $allowedDescriptions, true)) {
        $description = 'ALL';
    }

    // Fetch filtered results
    $machines = getFilteredMachines(20, 0, $search, $description, 0);

    if (empty($machines)) {
        echo json_encode([
            'success' => false,
            'error' => 'emptyDb'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => $machines
    ]);

} catch (Exception $e) {
    // Return error as JSON
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}  