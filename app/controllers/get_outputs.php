<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Get applicator ID from request
    $applicator_id = $_GET['applicator_id'] ?? null;
    
    if ($applicator_id) {
        // Get specific applicator
        $sql = "SELECT * FROM applicator_outputs WHERE applicator_id = :applicator_id AND is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $outputs = calculateProgressPercentages($result);
            echo json_encode(['success' => true, 'data' => $outputs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Applicator not found']);
        }
    } else {
        // Get all active applicators
        $sql = "SELECT * FROM applicator_outputs WHERE is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $outputs = [];
        foreach ($results as $result) {
            $outputs[] = calculateProgressPercentages($result);
        }
        
        echo json_encode(['success' => true, 'data' => $outputs]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

function calculateProgressPercentages($data) {
    // 400k group
    $wire_crimper_progress = min(100, round(($data['wire_crimper'] / 400000.0) * 100, 2));
    $wire_anvil_progress = min(100, round(($data['wire_anvil'] / 400000.0) * 100, 2));
    $insulation_crimper_progress = min(100, round(($data['insulation_crimper'] / 400000.0) * 100, 2));
    $insulation_anvil_progress = min(100, round(($data['insulation_anvil'] / 400000.0) * 100, 2));
    $slide_cutter_progress = min(100, round(($data['slide_cutter'] / 400000.0) * 100, 2));
    
    // 500k group
    $cutter_holder_progress = min(100, round(($data['cutter_holder'] / 500000.0) * 100, 2));
    $shear_blade_progress = min(100, round(($data['shear_blade'] / 500000.0) * 100, 2));
    
    // 600k group
    $cutter_a_progress = min(100, round(($data['cutter_a'] / 600000.0) * 100, 2));
    $cutter_b_progress = min(100, round(($data['cutter_b'] / 600000.0) * 100, 2));
    
    // Custom parts progress
    $custom_parts_progress = 0;
    if (!empty($data['custom_parts'])) {
        $custom_data = json_decode($data['custom_parts'], true);
        if (isset($custom_data['output'])) {
            $custom_parts_progress = min(100, round(($custom_data['output'] / 600000.0) * 100, 2));
        }
    }
    
    return [
        'applicator_id' => $data['applicator_id'],
        'hp_no' => $data['hp_no'] ?? '',
        'progress' => [
            'wire_crimper' => [
                'current' => $data['wire_crimper'],
                'limit' => 400000,
                'percentage' => $wire_crimper_progress,
                'status' => getStatusColor($wire_crimper_progress)
            ],
            'wire_anvil' => [
                'current' => $data['wire_anvil'],
                'limit' => 400000,
                'percentage' => $wire_anvil_progress,
                'status' => getStatusColor($wire_anvil_progress)
            ],
            'insulation_crimper' => [
                'current' => $data['insulation_crimper'],
                'limit' => 400000,
                'percentage' => $insulation_crimper_progress,
                'status' => getStatusColor($insulation_crimper_progress)
            ],
            'insulation_anvil' => [
                'current' => $data['insulation_anvil'],
                'limit' => 400000,
                'percentage' => $insulation_anvil_progress,
                'status' => getStatusColor($insulation_anvil_progress)
            ],
            'slide_cutter' => [
                'current' => $data['slide_cutter'],
                'limit' => 400000,
                'percentage' => $slide_cutter_progress,
                'status' => getStatusColor($slide_cutter_progress)
            ],
            'cutter_holder' => [
                'current' => $data['cutter_holder'],
                'limit' => 500000,
                'percentage' => $cutter_holder_progress,
                'status' => getStatusColor($cutter_holder_progress)
            ],
            'shear_blade' => [
                'current' => $data['shear_blade'],
                'limit' => 500000,
                'percentage' => $shear_blade_progress,
                'status' => getStatusColor($shear_blade_progress)
            ],
            'cutter_a' => [
                'current' => $data['cutter_a'],
                'limit' => 600000,
                'percentage' => $cutter_a_progress,
                'status' => getStatusColor($cutter_a_progress)
            ],
            'cutter_b' => [
                'current' => $data['cutter_b'],
                'limit' => 600000,
                'percentage' => $cutter_b_progress,
                'status' => getStatusColor($cutter_b_progress)
            ],
            'custom_parts' => [
                'current' => $custom_data['output'] ?? 0,
                'limit' => 600000,
                'percentage' => $custom_parts_progress,
                'status' => getStatusColor($custom_parts_progress)
            ]
        ]
    ];
}

function getStatusColor($percentage) {
    if ($percentage < 70) return 'green';    // OK
    if ($percentage < 90) return 'yellow';   // Warning
    return 'red';                            // Replace
}
?>
