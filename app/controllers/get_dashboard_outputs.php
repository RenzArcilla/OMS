<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Get applicator ID from request
    $applicator_id = $_GET['applicator_id'] ?? null;
    
    // Include the same model that the dashboard uses
    require_once '../models/read_custom_parts.php';
    require_once '../models/read_applicator_limits.php';
    require_once '../models/read_joins/read_monitor_applicator_and_applicator.php';
    
    // Get custom parts (same as dashboard)
    $custom_applicator_parts = getCustomParts("APPLICATOR");
    $part_names_array = [];

    // Get all part limits of applicators
    $part_limits = getPartLimitsOfApplicators("APPLICATOR");
    $limits = [];
    foreach ($part_limits as $row) {
        // join key1 and key2 into one string key
        $composite_key = $row['applicator_id'] . '|' . $row['applicator_part'];
        $limits[$composite_key] = $row['part_limit'];
    }

    foreach ($custom_applicator_parts as $part) {
        $part_names_array[] = $part['part_name'];
    }
    
    if ($applicator_id) {
        // Get specific applicator using same logic as dashboard
        $search_result = searchApplicatorByHpNo($applicator_id, $part_names_array);
        
        if (!empty($search_result)) {
            $outputs = calculateDashboardProgressPercentages($search_result[0]);
            echo json_encode(['success' => true, 'data' => $outputs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Applicator not found']);
        }
    } else {
        // Get all applicators using same logic as dashboard
        $applicator_total_outputs = getApplicatorRecordsAndOutputs(10, 0, $part_names_array);
        
        $outputs = [];
        foreach ($applicator_total_outputs as $row) {
            $outputs[] = calculateDashboardProgressPercentages($row);
        }
        
        echo json_encode(['success' => true, 'data' => $outputs]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

function calculateDashboardProgressPercentages($data) {
    // 400k group - handle reset values (0 means reset)
    $wire_crimper_progress = $data['wire_crimper_output'] <= 0 ? 0 : min(100, round(($data['wire_crimper_output'] / 400000.0) * 100, 2));
    if ($wire_crimper_progress < 0.01) $wire_crimper_progress = 0;
    
    $wire_anvil_progress = $data['wire_anvil_output'] <= 0 ? 0 : min(100, round(($data['wire_anvil_output'] / 400000.0) * 100, 2));
    if ($wire_anvil_progress < 0.01) $wire_anvil_progress = 0;
    
    $insulation_crimper_progress = $data['insulation_crimper_output'] <= 0 ? 0 : min(100, round(($data['insulation_crimper_output'] / 400000.0) * 100, 2));
    if ($insulation_crimper_progress < 0.01) $insulation_crimper_progress = 0;
    
    $insulation_anvil_progress = $data['insulation_anvil_output'] <= 0 ? 0 : min(100, round(($data['insulation_anvil_output'] / 400000.0) * 100, 2));
    if ($insulation_anvil_progress < 0.01) $insulation_anvil_progress = 0;
    
    $slide_cutter_progress = $data['slide_cutter_output'] <= 0 ? 0 : min(100, round(($data['slide_cutter_output'] / 400000.0) * 100, 2));
    if ($slide_cutter_progress < 0.01) $slide_cutter_progress = 0;
    
    // 500k group
    $cutter_holder_progress = $data['cutter_holder_output'] <= 0 ? 0 : min(100, round(($data['cutter_holder_output'] / 500000.0) * 100, 2));
    if ($cutter_holder_progress < 0.01) $cutter_holder_progress = 0;
    
    $shear_blade_progress = $data['shear_blade_output'] <= 0 ? 0 : min(100, round(($data['shear_blade_output'] / 500000.0) * 100, 2));
    if ($shear_blade_progress < 0.01) $shear_blade_progress = 0;
    
    // 600k group
    $cutter_a_progress = $data['cutter_a_output'] <= 0 ? 0 : min(100, round(($data['cutter_a_output'] / 600000.0) * 100, 2));
    if ($cutter_a_progress < 0.01) $cutter_a_progress = 0;
    
    $cutter_b_progress = $data['cutter_b_output'] <= 0 ? 0 : min(100, round(($data['cutter_b_output'] / 600000.0) * 100, 2));
    if ($cutter_b_progress < 0.01) $cutter_b_progress = 0;
    
    // Build the progress array
    $progress = [
        'wire_crimper' => [
            'current' => $data['wire_crimper_output'],
            'limit' => 400000,
            'percentage' => $wire_crimper_progress,
            'status' => getStatusColor($wire_crimper_progress)
        ],
        'wire_anvil' => [
            'current' => $data['wire_anvil_output'],
            'limit' => 400000,
            'percentage' => $wire_anvil_progress,
            'status' => getStatusColor($wire_anvil_progress)
        ],
        'insulation_crimper' => [
            'current' => $data['insulation_crimper_output'],
            'limit' => 400000,
            'percentage' => $insulation_crimper_progress,
            'status' => getStatusColor($insulation_crimper_progress)
        ],
        'insulation_anvil' => [
            'current' => $data['insulation_anvil_output'],
            'limit' => 400000,
            'percentage' => $insulation_anvil_progress,
            'status' => getStatusColor($insulation_anvil_progress)
        ],
        'slide_cutter' => [
            'current' => $data['slide_cutter_output'],
            'limit' => 400000,
            'percentage' => $slide_cutter_progress,
            'status' => getStatusColor($slide_cutter_progress)
        ],
        'cutter_holder' => [
            'current' => $data['cutter_holder_output'],
            'limit' => 500000,
            'percentage' => $cutter_holder_progress,
            'status' => getStatusColor($cutter_holder_progress)
        ],
        'shear_blade' => [
            'current' => $data['shear_blade_output'],
            'limit' => 500000,
            'percentage' => $shear_blade_progress,
            'status' => getStatusColor($shear_blade_progress)
        ],
        'cutter_a' => [
            'current' => $data['cutter_a_output'],
            'limit' => 600000,
            'percentage' => $cutter_a_progress,
            'status' => getStatusColor($cutter_a_progress)
        ],
        'cutter_b' => [
            'current' => $data['cutter_b_output'],
            'limit' => 600000,
            'percentage' => $cutter_b_progress,
            'status' => getStatusColor($cutter_b_progress)
        ]
    ];
    
    // Add custom parts to progress data
    if (!empty($data['custom_parts_output']) && is_array($data['custom_parts_output'])) {
        foreach ($data['custom_parts_output'] as $partName => $total) {
            // Ensure total is a valid number and default to 0 if not
            $total = is_numeric($total) ? (int)$total : 0;
            
            // For custom parts, ensure 0 shows as exactly 0% (no tiny progress bar)
            // Use strict comparison and handle very small values
            if ($total <= 0) {
                $custom_progress = 0;
            } else {
                $custom_progress = min(100, round(($total / 600000.0) * 100, 2));
                // Additional check: if the calculated percentage is very small (less than 0.01%), set it to 0
                if ($custom_progress < 0.01) {
                    $custom_progress = 0;
                }
            }
            
            $progress["custom_parts_$partName"] = [
                'current' => $total,
                'limit' => 600000,
                'percentage' => $custom_progress,
                'status' => getStatusColor($custom_progress)
            ];
        }
    }
    
    return [
        'applicator_id' => $data['applicator_id'],
        'hp_no' => $data['hp_no'] ?? '',
        'progress' => $progress
    ];
}

function getStatusColor($percentage) {
    if ($percentage < 70) return 'green';    // OK
    if ($percentage < 90) return 'yellow';   // Warning
    return 'red';                            // Replace
}
