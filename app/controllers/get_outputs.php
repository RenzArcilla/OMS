<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    // Get applicator ID from request
    $applicator_id = $_GET['applicator_id'] ?? null;
    
    if ($applicator_id) {
        // Get and aggregate all active records for a specific applicator
        $sql = "SELECT * FROM applicator_outputs WHERE applicator_id = :applicator_id AND is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows && count($rows) > 0) {
            $aggregated = aggregateApplicatorRows($rows);
            $outputs = calculateProgressPercentages($aggregated);
            echo json_encode(['success' => true, 'data' => $outputs]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Applicator not found']);
        }
    } else {
        // Get and aggregate all active records for all applicators
        $sql = "SELECT * FROM applicator_outputs WHERE is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $by_applicator = [];
        foreach ($rows as $row) {
            $id = $row['applicator_id'];
            if (!isset($by_applicator[$id])) {
                $by_applicator[$id] = [];
            }
            $by_applicator[$id][] = $row;
        }

        $outputs = [];
        foreach ($by_applicator as $id => $groupRows) {
            $aggregated = aggregateApplicatorRows($groupRows);
            $outputs[] = calculateProgressPercentages($aggregated);
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
    
    // Build the progress array
    $progress = [
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
        ]
    ];
    
    // Add custom parts to progress data
    if (!empty($data['custom_parts'])) {
        if (is_array($data['custom_parts'])) {
            // New format with individual part totals
            foreach ($data['custom_parts'] as $partName => $total) {
                if ($partName !== 'legacy_total') {
                    $custom_progress = min(100, round(($total / 600000.0) * 100, 2));
                    $progress["custom_parts_$partName"] = [
                        'current' => $total,
                        'limit' => 600000,
                        'percentage' => $custom_progress,
                        'status' => getStatusColor($custom_progress)
                    ];
                }
            }
        } else {
            // Legacy format - decode JSON
            $decoded = json_decode($data['custom_parts'], true);
            if (is_array($decoded) && isset($decoded['output'])) {
                $custom_progress = min(100, round(($decoded['output'] / 600000.0) * 100, 2));
                $progress['custom_parts'] = [
                    'current' => $decoded['output'],
                    'limit' => 600000,
                    'percentage' => $custom_progress,
                    'status' => getStatusColor($custom_progress)
                ];
            }
        }
    }
    
    return [
        'applicator_id' => $data['applicator_id'],
        'hp_no' => $data['hp_no'] ?? '',
        'progress' => $progress
    ];
}

function aggregateApplicatorRows(array $rows) {
    // Sum numeric columns across rows and synthesize custom_parts total
    $numericFields = [
        'total_output', 'wire_crimper', 'wire_anvil', 'insulation_crimper', 'insulation_anvil',
        'slide_cutter', 'cutter_holder', 'shear_blade', 'cutter_a', 'cutter_b'
    ];

    $agg = [];
    // Preserve applicator_id; hp_no if present on any row
    $agg['applicator_id'] = $rows[0]['applicator_id'] ?? null;
    if (isset($rows[0]['hp_no'])) {
        $agg['hp_no'] = $rows[0]['hp_no'];
    }

    foreach ($numericFields as $field) {
        $agg[$field] = 0;
    }

    // Track custom parts individually
    $customPartsTotals = [];

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
