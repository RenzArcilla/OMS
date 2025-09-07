<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    // Get custom machine parts
    $sql = "SELECT * FROM custom_part_definitions WHERE equipment_type = 'MACHINE' AND is_active = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $custom_parts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get a sample machine to see its custom parts output
    $sql = "SELECT machine_id, control_no, custom_parts_output FROM monitor_machine LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sample_machine = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $result = [
        'custom_parts_defined' => $custom_parts,
        'sample_machine' => $sample_machine,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo json_encode($result, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
