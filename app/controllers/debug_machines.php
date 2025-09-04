<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Check which machines have records in monitor_machine table
    $sql = "SELECT mm.machine_id, mm.cut_blade_output, mm.strip_blade_a_output, mm.strip_blade_b_output, m.control_no
            FROM monitor_machine mm 
            LEFT JOIN machines m ON mm.machine_id = m.machine_id
            WHERE mm.is_active = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $monitor_machines = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check all machines
    $sql = "SELECT machine_id, control_no FROM machines WHERE is_active = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $all_machines = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $result = [
        'monitor_machines' => $monitor_machines,
        'all_machines' => $all_machines,
        'machines_with_monitor' => count($monitor_machines),
        'total_machines' => count($all_machines),
        'missing_monitor' => []
    ];
    
    // Find machines without monitor records
    $monitor_ids = array_column($monitor_machines, 'machine_id');
    foreach ($all_machines as $machine) {
        if (!in_array($machine['machine_id'], $monitor_ids)) {
            $result['missing_monitor'][] = $machine;
        }
    }
    
    echo json_encode($result, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
