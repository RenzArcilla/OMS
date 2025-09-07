<?php
/*
    Controller: Handles progress bar computation for the Machine Dashboard.
    
    Responsibilities:
    - Accepts JSON payload from ProgressBarManager (DOM -> JS -> PHP).
    - Maps each machine/part output to its configured part limits.
    - Computes percentage usage and assigns a status (green/yellow/red).
    - Returns JSON response with computed progress per machine.
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Output JSON
header('Content-Type: application/json');

// Include dependencies
require_once __DIR__ . '/../models/read_machine_limits.php';

try {
    /*
        Step 1: Decode the request payload
        Example:
        {
          "machines": [
            {
              "machine_id": "M1",
              "parts": {
                "cut_blade": { "current": 123456, "limit": 2000000 }
              }
            }
          ]
        }
    */
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['machines'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid payload']);
        exit;
    }

    /*
        Step 2: Load machine part limits from DB
        - machine_part_limits table contains per-machine overrides.
        - Build a map like:
          [
            "M1|cut_blade"    => 2000000,
            "M2|strip_blade_a" => 1500000
          ]
    */
    $part_limits = getMachinePartLimits();
    $limits = [];
    foreach ($part_limits as $row) {
        $composite_key = $row['machine_id'] . '|' . $row['machine_part'];
        $limits[$composite_key] = $row['part_limit'];
    }

    /*
        Step 3: Process each machine from the payload
        - For each part, decide the correct limit:
          1. Use machine-specific override if exists.
          2. Else use default per part name (fallback).
          3. Else fallback to 1.5M.
        - Compute percentage + color status.
    */
    $responseData = [];

    foreach ($input['machines'] as $machine) {
        $machineId = $machine['machine_id'] ?? null;
        $parts = $machine['parts'] ?? [];

        if (!$machineId) continue;

        $progress = [];

        foreach ($parts as $partName => $partData) {
            $current = (int)($partData['current'] ?? 0);

            // Strip "custom_parts_" if present
            $normalizedPart = preg_replace('/^custom_parts_/', '', $partName);

            // Lookup machine-specific override, else fallback
            $composite_key = $machineId . '|' . $normalizedPart;
            $limit = $limits[$composite_key] 
                ?? getDefaultLimitForPart($normalizedPart) 
                ?? 1500000;

            // Compute % usage and assign a status
            $percentage = getPercent($current, $limit);
            $status = getPercentColor($current, $limit);

            // Keep original $partName so JS can still map to the DOM <td>
            $progress[$partName] = [
                'current'    => $current,
                'limit'      => $limit,
                'percentage' => $percentage,
                'status'     => $status,
            ];
        }

        $responseData[] = [
            'machine_id' => $machineId,
            'progress'   => $progress,
        ];
    }

    /*
        Step 4: Return final response
        - Shape matches what MachineProgressBarManager expects.
    */
    echo json_encode(['success' => true, 'data' => $responseData]);

} catch (Exception $e) {
    // Log and return error JSON
    error_log('Dashboard machine error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error']);
}


/* -------------------------- HELPERS -------------------------- */

function getPercent($output, $limit) {
    /*
        Compute percentage = output / limit * 100
    */
    if ($output <= 0 || $limit <= 0) return 0;
    return round(($output / $limit) * 100);
}

function getPercentColor($output, $limit) {
    /*
        Map percentage to status color
        - green   = safe (< 70%)
        - yellow  = warning (70%–89%)
        - red     = critical (>= 90%)
    */
    $percent = getPercent($output, $limit);
    if ($percent < 70) return 'green';
    if ($percent < 90) return 'yellow';
    return 'red';
}

function getDefaultLimitForPart($part) {
    /*
        Machine fallback limits per part type if no DB override is found
    */
    return 1500000; // universal fallback for all parts
}
