<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Get machine ID from request
    $machine_id = $_GET['machine_id'] ?? null;
    
    if ($machine_id) {
        // Get and aggregate all active records for a specific machine with reset data
        $sql = "SELECT mo.*, mm.cut_blade_output as mm_cut_blade, mm.strip_blade_a_output as mm_strip_blade_a,
                       mm.strip_blade_b_output as mm_strip_blade_b, mm.custom_parts_output as mm_custom_parts
                FROM machine_outputs mo 
                LEFT JOIN monitor_machine mm ON mo.machine_id = mm.machine_id
                WHERE mo.machine_id = :machine_id AND mo.is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows && count($rows) > 0) {
            $aggregated = aggregateMachineRows($rows);
            $outputs = calculateMachineProgressPercentages($aggregated);
            echo json_encode(['success' => true, 'data' => $outputs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Machine not found']);
        }
    } else {
        // Get and aggregate all active records for all machines with reset data
        $sql = "SELECT mo.*, mm.cut_blade_output as mm_cut_blade, mm.strip_blade_a_output as mm_strip_blade_a,
                       mm.strip_blade_b_output as mm_strip_blade_b, mm.custom_parts_output as mm_custom_parts
                FROM machine_outputs mo 
                LEFT JOIN monitor_machine mm ON mo.machine_id = mm.machine_id
                WHERE mo.is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $by_machine = [];
        foreach ($rows as $row) {
            $id = $row['machine_id'];
            if (!isset($by_machine[$id])) {
                $by_machine[$id] = [];
            }
            $by_machine[$id][] = $row;
        }

        $outputs = [];
        foreach ($by_machine as $id => $groupRows) {
            $aggregated = aggregateMachineRows($groupRows);
            $outputs[] = calculateMachineProgressPercentages($aggregated);
        }

        echo json_encode(['success' => true, 'data' => $outputs]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

function calculateMachineProgressPercentages($data) {
    // Machine parts have different limits than applicators
    // Cut Blade: 2M limit
    $cut_blade_progress = min(100, round(($data['cut_blade'] / 2000000.0) * 100, 2));
    
    // Strip Blade A: 1.5M limit
    $strip_blade_a_progress = min(100, round(($data['strip_blade_a'] / 1500000.0) * 100, 2));
    
    // Strip Blade B: 1.5M limit
    $strip_blade_b_progress = min(100, round(($data['strip_blade_b'] / 1500000.0) * 100, 2));
    
    // Build the progress array
    $progress = [
        'cut_blade' => [
            'current' => $data['cut_blade'],
            'limit' => 2000000,
            'percentage' => $cut_blade_progress,
            'status' => getStatusColor($cut_blade_progress)
        ],
        'strip_blade_a' => [
            'current' => $data['strip_blade_a'],
            'limit' => 1500000,
            'percentage' => $strip_blade_a_progress,
            'status' => getStatusColor($strip_blade_a_progress)
        ],
        'strip_blade_b' => [
            'current' => $data['strip_blade_b'],
            'limit' => 1500000,
            'percentage' => $strip_blade_b_progress,
            'status' => getStatusColor($strip_blade_b_progress)
        ]
    ];
    
    // Add custom parts to progress data
    if (!empty($data['custom_parts'])) {
        if (is_array($data['custom_parts'])) {
            // New format with individual part totals
            foreach ($data['custom_parts'] as $partName => $total) {
                if ($partName !== 'legacy_total') {
                    $custom_progress = min(100, round(($total / 1500000.0) * 100, 2));
                    $progress["custom_parts_$partName"] = [
                        'current' => $total,
                        'limit' => 1500000,
                        'percentage' => $custom_progress,
                        'status' => getStatusColor($custom_progress)
                    ];
                }
            }
        } else {
            // Legacy format - decode JSON
            $decoded = json_decode($data['custom_parts'], true);
            if (is_array($decoded) && isset($decoded['output'])) {
                $custom_progress = min(100, round(($decoded['output'] / 1500000.0) * 100, 2));
                $progress['custom_parts'] = [
                    'current' => $decoded['output'],
                    'limit' => 1500000,
                    'percentage' => $custom_progress,
                    'status' => getStatusColor($custom_progress)
                ];
            }
        }
    }
    
    return [
        'machine_id' => $data['machine_id'],
        'control_no' => $data['control_no'] ?? '',
        'progress' => $progress
    ];
}

function aggregateMachineRows(array $rows) {
    // Sum numeric columns across rows and synthesize custom_parts total
    $numericFields = [
        'total_machine_output', 'cut_blade', 'strip_blade_a', 'strip_blade_b'
    ];

    $agg = [];
    // Preserve machine_id; control_no if present on any row
    $agg['machine_id'] = $rows[0]['machine_id'] ?? null;
    if (isset($rows[0]['control_no'])) {
        $agg['control_no'] = $rows[0]['control_no'];
    }

    foreach ($numericFields as $field) {
        $agg[$field] = 0;
    }

    // Track custom parts individually
    $customPartsTotals = [];

    // Get reset data from monitor_machine (use first row since all rows have same machine_id)
    $resetData = null;
    if (!empty($rows) && isset($rows[0]['mm_cut_blade'])) {
        $resetData = [
            'cut_blade' => $rows[0]['mm_cut_blade'],
            'strip_blade_a' => $rows[0]['mm_strip_blade_a'],
            'strip_blade_b' => $rows[0]['mm_strip_blade_b'],
            'custom_parts' => $rows[0]['mm_custom_parts']
        ];
    }

    foreach ($rows as $row) {
        foreach ($numericFields as $field) {
            if (isset($row[$field])) {
                $agg[$field] += (int)$row[$field];
            }
        }

        if (!empty($row['custom_parts'])) {
            $decoded = json_decode($row['custom_parts'], true);
            // Support either [{name, value}...] or {output: num}
            if (is_array($decoded)) {
                if (isset($decoded['output'])) {
                    // Legacy format - distribute total across all custom parts
                    if (!isset($customPartsTotals['legacy_total'])) {
                        $customPartsTotals['legacy_total'] = 0;
                    }
                    $customPartsTotals['legacy_total'] += (int)$decoded['output'];
                } else {
                    // New format with individual part names
                    foreach ($decoded as $entry) {
                        if (is_array($entry) && isset($entry['name']) && isset($entry['value'])) {
                            $partName = $entry['name'];
                            if (!isset($customPartsTotals[$partName])) {
                                $customPartsTotals[$partName] = 0;
                            }
                            $customPartsTotals[$partName] += (int)$entry['value'];
                        }
                    }
                }
            }
        }
    }

    // Apply reset logic - if a part was reset to 0, use 0 instead of aggregated value
    if ($resetData) {
        foreach ($numericFields as $field) {
            if ($field !== 'total_machine_output' && isset($resetData[$field]) && $resetData[$field] === 0) {
                $agg[$field] = 0;
            }
        }
        
        // Handle custom parts reset
        if (!empty($resetData['custom_parts'])) {
            $resetCustomParts = json_decode($resetData['custom_parts'], true);
            if (is_array($resetCustomParts)) {
                foreach ($resetCustomParts as $partName => $value) {
                    if ($value === 0) {
                        $customPartsTotals[$partName] = 0;
                    }
                }
            }
        }
    }

    // Store custom parts data
    $agg['custom_parts'] = $customPartsTotals;

    return $agg;
}

function getStatusColor($percentage) {
    if ($percentage < 70) return 'green';    // OK
    if ($percentage < 90) return 'yellow';   // Warning
    return 'red';                            // Replace
}
?>
