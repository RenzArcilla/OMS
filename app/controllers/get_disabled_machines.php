<?php
/*
    Controller file for handling disabled-machine pagination requests.
    It retrieves query parameters from the frontend (page, items_per_page, search, filters),
    calls the model function to fetch the paginated disabled-machines,
    and returns the results in JSON format with pagination metadata.
*/
require_once __DIR__ . '/../models/read_machines.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $items_per_page = isset($_GET['items_per_page']) ? max(5, min(50, (int)$_GET['items_per_page'])) : 10;
    $offset = ($current_page - 1) * $items_per_page;
    
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search === '') $search = null;
    
    $description = isset($_GET['description']) ? strtoupper(trim($_GET['description'])) : 'ALL';
    $allowedDescriptions = ['ALL', 'AUTOMATIC', 'SEMI-AUTOMATIC'];
    if (!in_array($description, $allowedDescriptions, true)) {
        $description = 'ALL';
    }
    
    $total_records = getDisabledMachinesCount($search, $description);
    $total_pages = ceil($total_records / $items_per_page);
    
    $machines = getFilteredMachines($items_per_page, $offset, $search, $description, 0);
    
    $showing_from = $offset + 1;
    $showing_to = min($offset + $items_per_page, $total_records);
    
    echo json_encode([
        'success' => true,
        'data' => $machines,
        'pagination' => [
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'items_per_page' => $items_per_page,
            'showing_from' => $showing_from,
            'showing_to' => $showing_to
        ],
        'empty_db' => empty($machines)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
