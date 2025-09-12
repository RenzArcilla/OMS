<?php
/*
    Controller: Handles progress bar computation for the Applicator Dashboard.
    
    Responsibilities:
    - Accepts JSON payload from ProgressBarManager (DOM -> JS -> PHP).
    - Maps each applicator/part output to its configured part limits.
    - Computes percentage usage and assigns a status (green/yellow/red).
    - Returns JSON response with computed progress per applicator.
*/

// Output JSON
header('Content-Type: application/json');

// Include dependencies
require_once __DIR__ . '/../models/read_applicator_limits.php';

try {
    /*
        Step 1: Decode the request payload
        - ProgressBarManager sends POST JSON with applicator_id + parts (current, limit from DOM).
        - Example:
          {
            "applicators": [
              {
                "applicator_id": "A1",
                "parts": {
                  "wire_crimper": { "current": 123456, "limit": 400000 }
                }
              }
            ]
          }
    */
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['applicators'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid payload']);
        exit;
    }

    /*
        Step 2: Load applicator part limits from DB
        - applicator_part_limits table contains per-applicator overrides.
        - Build a map like:
          [
            "A1|wire_crimper" => 400000,
            "A2|shear_blade"  => 500000
          ]
    */
    $part_limits = getApplicatorPartLimits();
    $limits = [];
    foreach ($part_limits as $row) {
        $composite_key = $row['applicator_id'] . '|' . $row['applicator_part'];
        $limits[$composite_key] = $row['part_limit'];
    }

    /*
        Step 3: Process each applicator from the payload
        - For each part, decide the correct limit:
          1. Use applicator-specific override if exists.
          2. Else use default per part name (fallback).
          3. Else fallback to 500k.
        - Compute percentage + color status.
    */
    $responseData = [];

    foreach ($input['applicators'] as $applicator) {
        $applicatorId = $applicator['applicator_id'] ?? null;
        $parts = $applicator['parts'] ?? [];

        if (!$applicatorId) continue;

        $progress = [];

        foreach ($parts as $partName => $partData) {
        $current = (int)($partData['current'] ?? 0);

        // Strip "custom_parts_" if present
        $normalizedPart = preg_replace('/^custom_parts_/', '', $partName);

        // Lookup applicator-specific override, else fallback
        $composite_key = $applicatorId . '|' . $normalizedPart;
        $limit = $limits[$composite_key] 
            ?? getDefaultLimitForPart($normalizedPart) 
            ?? 500000;

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
            'applicator_id' => $applicatorId,
            'progress'      => $progress,
        ];
    }

    /*
        Step 4: Return final response
        - Shape matches what ProgressBarManager expects.
    */
    echo json_encode(['success' => true, 'data' => $responseData]);

} catch (Exception $e) {
    // Log and return error JSON
    error_log('Dashboard outputs error: ' . $e->getMessage());
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
        Fallback limits per part type if no DB override is found
    */
    if (in_array($part, ['wire_crimper','wire_anvil','insulation_crimper','insulation_anvil','slide_cutter'])) return 500000;
    if (in_array($part, ['cutter_holder','shear_blade'])) return 500000;
    return 500000; // cutter_a, cutter_b, and custom parts
}
