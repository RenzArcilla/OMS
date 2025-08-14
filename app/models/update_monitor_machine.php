<?php
/*
    This script updates the cumulative sum of machine outputs.
    Supports incrementing or decrementing standard counters and JSON values for custom parts.
*/

require_once __DIR__ . '/../includes/db.php';

function monitorMachineOutput($machine_data, $machine_output, $operation = 'increment') {
    /*
    Function to update machine outputs (increment or decrement).

    Args:
    - $machine_data: Machine data array or just machine_id.
    - $machine_output: Output value to adjust by.
    - $operation: 'increment' or 'decrement'.

    Returns:
    - True if successful.
    - String with error message if failed.
    */
    global $pdo;

    try {
        $machine_id = is_array($machine_data) ? $machine_data['machine_id'] : $machine_data;

        // Always use absolute value, then apply operation sign
        $machine_output = abs($machine_output);
        if (strtolower($operation) === "decrement") {
            $machine_output = -$machine_output;
        }

        // Fetch applicable custom parts for machines
        require_once __DIR__ . '/read_custom_parts.php';
        $custom_parts = getCustomParts('MACHINE');
        
        // If custom parts retrieval failed (string message returned), pass it back
        if (is_string($custom_parts)) {
            return $custom_parts;
        }

        // Build an array of part => output
        $new_parts = [];
        foreach ($custom_parts as $part) {
            $new_parts[$part['part_name']] = $machine_output;
        }

        // Check if there's an existing record for this machine
        $stmt_check = $pdo->prepare("
            SELECT custom_parts_output 
            FROM monitor_machine 
            WHERE machine_id = :id 
            LIMIT 1
        ");
        $stmt_check->execute([':id' => $machine_id]);
        $existing = $stmt_check->fetchColumn();

        // Merge with existing custom parts data if it exists
        if ($existing) {
            $existing_parts = json_decode($existing, true) ?? [];
            foreach ($new_parts as $key => $val) {
                $existing_parts[$key] = ($existing_parts[$key] ?? 0) + $val;
            }
            $custom_parts_json = json_encode($existing_parts);
        } else {
            $custom_parts_json = json_encode($new_parts);
        }

        // Update DB
        $stmt = $pdo->prepare("
            INSERT INTO monitor_machine (
                machine_id, total_machine_output, cut_blade_output, 
                strip_blade_a_output, strip_blade_b_output, custom_parts_output, last_updated
            ) VALUES (
                :machine_id, :val, :val, :val, :val, :custom_json, CURRENT_TIMESTAMP
            )
            ON DUPLICATE KEY UPDATE
                total_machine_output = total_machine_output + :val,
                cut_blade_output = cut_blade_output + :val,
                strip_blade_a_output = strip_blade_a_output + :val,
                strip_blade_b_output = strip_blade_b_output + :val,
                custom_parts_output = :custom_json,
                last_updated = CURRENT_TIMESTAMP
        ");
        
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->bindParam(':val', $machine_output, PDO::PARAM_INT);
        $stmt->bindParam(':custom_json', $custom_parts_json, PDO::PARAM_STR);

        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        error_log("DB Error in monitorMachineOutput(): " . $e->getMessage());
        return "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}