<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$machine_id = $_GET['machine_id'] ?? null;
$part_name = $_GET['part_name'] ?? null;
$action = $_GET['action'] ?? 'check';

if (!$machine_id) {
    echo json_encode(['error' => 'Machine ID required']);
    exit;
}

if ($action === 'reset' && $part_name) {
    require_once __DIR__ . '/../models/update_monitor_machine.php';
    $result = resetMachinePartOutput($machine_id, $part_name);
    echo json_encode(['success' => $result === true, 'message' => $result]);
    exit;
}

// Get current state
$sql = "SELECT mm.*, m.control_no FROM monitor_machine mm LEFT JOIN machines m ON mm.machine_id = m.machine_id WHERE mm.machine_id = :machine_id AND mm.is_active = 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
$stmt->execute();
$monitor_data = $stmt->fetch(PDO::FETCH_ASSOC);

$result = [
    'machine_id' => $machine_id,
    'part_name' => $part_name,
    'action' => $action,
    'monitor_data' => $monitor_data,
    'timestamp' => date('Y-m-d H:i:s')
];

echo json_encode($result, JSON_PRETTY_PRINT);
?>
