<?php
/*
    This script handles both the CREATE and UPDATE operation for recording the cumulative sum of machine outputs.
    It updates standard part counters and increments JSON values for custom parts.
*/

require_once __DIR__ . '/../includes/db.php';

function monitorMachineOutput($machine_data, $machine_output) {
    /*
    Function to monitor cumulative outputs of each machine and its components.

    Args:
    - $machine_data: The data of the machine.
    - $machine_output: The output value of the machine.

    Returns:
    - True if the monitoring is successful.
    - String containing error message and redirect using JS <alert>.
    */

    global $pdo;

    try {
        $machine_id = is_array($machine_data) ? $machine_data['machine_id'] : $machine_data;

        // Fetch applicable custom parts
        require_once __DIR__ . '/read_custom_parts.php';
        $custom_parts = getCustomParts('MACHINE');

        if (is_string($custom_parts)) {
            return $custom_parts; // Error from getCustomParts
        }

        // Build new parts to add (e.g., {"partA": 50, "partB": 50})
        $new_parts = [];
        foreach ($custom_parts as $part) {
            $new_parts[$part['part_name']] = $machine_output;
        }

        // Fetch existing JSON (if any)
        $stmt_check = $pdo->prepare("SELECT custom_parts_output FROM monitor_machine WHERE machine_id = :id LIMIT 1");
        $stmt_check->execute([':id' => $machine_id]);
        $existing = $stmt_check->fetchColumn();

        if ($existing) {
            $existing_parts = json_decode($existing, true) ?? [];
            // Merge and increment values
            foreach ($new_parts as $key => $val) {
                if (isset($existing_parts[$key])) {
                    $existing_parts[$key] += $val;
                } else {
                    $existing_parts[$key] = $val;
                }
            }
            $custom_parts_json = json_encode($existing_parts);
        } else {
            $custom_parts_json = json_encode($new_parts);
        }

        $stmt = $pdo->prepare("
                    INSERT INTO monitor_machine (
                        machine_id, total_machine_output, cut_blade_output, strip_blade_a_output, 
                        strip_blade_b_output, custom_parts_output, last_updated
                    ) VALUES (
                        :machine_id, :val, :val, :val,
                        :val, :custom_json, CURRENT_TIMESTAMP
                    )
                    ON DUPLICATE KEY UPDATE
                        total_machine_output = total_machine_output + :val,
                        cut_blade_output = cut_blade_output + :val,
                        strip_blade_a_output = strip_blade_a_output + :val,
                        strip_blade_b_output = strip_blade_b_output + :val,
                        custom_parts_output = :custom_json,
                        last_updated = CURRENT_TIMESTAMP
                ");

        // Bind parameters
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
