<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    // Include the same model that the dashboard uses
    require_once __DIR__ . '/../models/read_joins/read_monitor_machine_and_machine.php';
    require_once __DIR__ . '/../models/read_custom_parts.php';
    
    // Get custom parts (same as dashboard)
    $custom_machine_parts = getCustomParts("MACHINE");
    $part_names_array = [];
    foreach ($custom_machine_parts as $part) {
        $part_names_array[] = $part['part_name'];
    }
    
    // Get all machines
    $items_per_page = 1000;
    $offset = 0;
    $machine_total_outputs = getMachineRecordsAndOutputs($items_per_page, $offset, $part_names_array);
    
    // Debug: Show raw data for first few machines
    $debug_data = [];
    $count = 0;
    foreach ($machine_total_outputs as $row) {
        if ($count < 3) { // Only show first 3 machines
            $debug_data[] = [
                'machine_id' => $row['machine_id'],
                'control_no' => $row['control_no'],
                'custom_parts_output' => $row['custom_parts_output'],
                'raw_custom_parts' => is_string($row['custom_parts_output']) ? json_decode($row['custom_parts_output'], true) : $row['custom_parts_output']
            ];
        }
        $count++;
    }
    
    echo json_encode([
        'success' => true,
        'debug_data' => $debug_data,
        'custom_parts_defined' => $part_names_array,
        'total_machines' => count($machine_total_outputs)
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
