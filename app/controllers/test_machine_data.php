<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    $machine_id = $_GET['machine_id'] ?? null;
    
    if (!$machine_id) {
        echo json_encode(['error' => 'Machine ID required']);
        exit;
    }
    
    // Get machine data from monitor_machine table
    $sql = "SELECT mm.*, m.control_no
            FROM monitor_machine mm 
            LEFT JOIN machines m ON mm.machine_id = m.machine_id
            WHERE mm.machine_id = :machine_id AND mm.is_active = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
    $stmt->execute();
    $monitor_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get machine data from machine_outputs table (for comparison)
    $sql = "SELECT SUM(total_machine_output) as total_output, 
                   SUM(cut_blade) as cut_blade_total,
                   SUM(strip_blade_a) as strip_blade_a_total,
                   SUM(strip_blade_b) as strip_blade_b_total
            FROM machine_outputs 
            WHERE machine_id = :machine_id AND is_active = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
    $stmt->execute();
    $outputs_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $result = [
        'machine_id' => $machine_id,
        'monitor_machine_data' => $monitor_data,
        'machine_outputs_summary' => $outputs_data,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo json_encode($result, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
