<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Test the get_machine_outputs.php endpoint
    $response = file_get_contents('http://localhost/SOMS/app/controllers/get_machine_outputs.php');
    $data = json_decode($response, true);
    
    echo json_encode([
        'test' => 'Machine outputs test',
        'response' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
