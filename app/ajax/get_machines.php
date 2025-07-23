<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/READ_machines.php';

$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

try {
    $machines = getMachines($pdo, $limit, $offset);

    header('Content-Type: application/json');
    echo json_encode($machines);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
