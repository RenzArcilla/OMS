<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Get machine ID from request
    $machine_id = $_GET['machine_id'] ?? null;
    
    // Include the same model that the dashboard uses
    require_once '../models/read_joins/read_monitor_machine_and_machine.php';
    require_once '../models/read_custom_parts.php';
    
    // Get custom parts (same as dashboard)
    $custom_machine_parts = getCustomParts("MACHINE");
    $part_names_array = [];
    foreach ($custom_machine_parts as $part) {
        $part_names_array[] = $part['part_name'];
    }
    
    
    if ($machine_id) {
        // Get specific machine using same logic as dashboard
        $search_result = searchMachineByControlNo($machine_id, $part_names_array);
        
        if (!empty($search_result)) {
            $outputs = calculateMachineProgressPercentages($search_result[0]);
            echo json_encode(['success' => true, 'data' => $outputs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Machine not found']);
        }
    } else {
        // Get all machines using same logic as dashboard - IMPORTANT: Use same pagination as dashboard
        $items_per_page = 1000; // Get all machines
        $offset = 0;
        $machine_total_outputs = getMachineRecordsAndOutputs($items_per_page, $offset, $part_names_array);
        
        $outputs = [];
        foreach ($machine_total_outputs as $row) {
            $outputs[] = calculateMachineProgressPercentages($row);
        }
        
        echo json_encode(['success' => true, 'data' => $outputs]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

function calculateMachineProgressPercentages($data) {
    // Debug: Log the data being processed
    error_log("Processing machine data: " . json_encode($data));
    
    // Additional debugging for custom parts
    if (!empty($data['custom_parts_output']) && is_array($data['custom_parts_output'])) {
        error_log("Custom parts data: " . json_encode($data['custom_parts_output']));
    }
    
    // Handle missing or null values
    $cut_blade_output = isset($data['cut_blade_output']) ? (int)$data['cut_blade_output'] : 0;
    $strip_blade_a_output = isset($data['strip_blade_a_output']) ? (int)$data['strip_blade_a_output'] : 0;
    $strip_blade_b_output = isset($data['strip_blade_b_output']) ? (int)$data['strip_blade_b_output'] : 0;
    
    // Machine parts have different limits than applicators
    // Cut Blade: 2M limit
    $cut_blade_progress = $cut_blade_output <= 0 ? 0 : min(100, round(($cut_blade_output / 2000000.0) * 100, 2));
    if ($cut_blade_progress < 0.01) $cut_blade_progress = 0;
    
    // Strip Blade A: 1.5M limit
    $strip_blade_a_progress = $strip_blade_a_output <= 0 ? 0 : min(100, round(($strip_blade_a_output / 1500000.0) * 100, 2));
    if ($strip_blade_a_progress < 0.01) $strip_blade_a_progress = 0;
    
    // Strip Blade B: 1.5M limit
    $strip_blade_b_progress = $strip_blade_b_output <= 0 ? 0 : min(100, round(($strip_blade_b_output / 1500000.0) * 100, 2));
    if ($strip_blade_b_progress < 0.01) $strip_blade_b_progress = 0;
    
    // Build the progress array
    $progress = [
        'cut_blade' => [
            'current' => $cut_blade_output,
            'limit' => 2000000,
            'percentage' => $cut_blade_progress,
            'status' => getStatusColor($cut_blade_progress)
        ],
        'strip_blade_a' => [
            'current' => $strip_blade_a_output,
            'limit' => 1500000,
            'percentage' => $strip_blade_a_progress,
            'status' => getStatusColor($strip_blade_a_progress)
        ],
        'strip_blade_b' => [
            'current' => $strip_blade_b_output,
            'limit' => 1500000,
            'percentage' => $strip_blade_b_progress,
            'status' => getStatusColor($strip_blade_b_progress)
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
                $custom_progress = min(100, round(($total / 1500000.0) * 100, 2));
                // Additional check: if the calculated percentage is very small (less than 0.01%), set it to 0
                if ($custom_progress < 0.01) {
                    $custom_progress = 0;
                }
            }
            
            $progress["custom_parts_$partName"] = [
                'current' => $total,
                'limit' => 1500000,
                'percentage' => $custom_progress,
                'status' => getStatusColor($custom_progress)
            ];
        }
    }
    
    return [
        'machine_id' => $data['machine_id'],
        'control_no' => $data['control_no'] ?? '',
        'progress' => $progress
    ];
}

function getStatusColor($percentage) {
    if ($percentage < 70) return 'green';    // OK
    if ($percentage < 90) return 'yellow';   // Warning
    return 'red';                            // Replace
}
