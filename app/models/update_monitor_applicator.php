<?php
/*
    This script handles both the CREATE and UPDATE operation for recording the cumulative sum of applicator outputs.
    It updates standard part counters and increments JSON values for custom parts.
*/

require_once __DIR__ . '/../includes/db.php';

function monitorApplicatorOutput($applicator_data, $applicator_output) {
    /*
    Function to monitor cumulative outputs of each applicator and its components.

    Args:
    - $applicator_data: The data of the applicator.
    - $applicator_output: The output value of the applicator.

    Returns:
    - True if the monitoring is successful.
    - String containing error message and redirect using JS <alert>.
    */

    global $pdo;

    try {
        $type = $applicator_data['description'];
        $applicator_id = is_array($applicator_data) ? $applicator_data['applicator_id'] : $applicator_data;

        // Fetch applicable custom parts
        require_once __DIR__ . '/read_custom_parts.php';
        $custom_parts = getCustomParts('APPLICATOR');

        if (is_string($custom_parts)) {
            return $custom_parts; // Error from getCustomParts
        }

        // Build new parts to add (e.g., {"partA": 50, "partB": 50})
        $new_parts = [];
        foreach ($custom_parts as $part) {
            $new_parts[$part['part_name']] = $applicator_output;
        }

        // Fetch existing JSON (if any)
        $stmt_check = $pdo->prepare("SELECT custom_parts_output FROM monitor_applicator WHERE applicator_id = :id LIMIT 1");
        $stmt_check->execute([':id' => $applicator_id]);
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

        // Determine query based on type
        switch ($type) {
            case "SIDE":
                $stmt = $pdo->prepare("
                    INSERT INTO monitor_applicator (
                        applicator_id, total_output, wire_crimper_output, wire_anvil_output, 
                        insulation_crimper_output, insulation_anvil_output, slide_cutter_output, 
                        cutter_holder_output, custom_parts_output, last_updated
                    ) VALUES (
                        :applicator_id, :val, :val, :val,
                        :val, :val, :val,
                        :val, :custom_json, CURRENT_TIMESTAMP
                    )
                    ON DUPLICATE KEY UPDATE
                        total_output = total_output + :val,
                        wire_crimper_output = wire_crimper_output + :val,
                        wire_anvil_output = wire_anvil_output + :val,
                        insulation_crimper_output = insulation_crimper_output + :val,
                        insulation_anvil_output = insulation_anvil_output + :val,
                        slide_cutter_output = slide_cutter_output + :val,
                        cutter_holder_output = cutter_holder_output + :val,
                        custom_parts_output = :custom_json,
                        last_updated = CURRENT_TIMESTAMP
                ");
                break;

            case "END":
                $stmt = $pdo->prepare("
                    INSERT INTO monitor_applicator (
                        applicator_id, total_output, wire_crimper_output, wire_anvil_output, 
                        insulation_crimper_output, insulation_anvil_output, shear_blade_output, 
                        cutter_a_output, cutter_b_output, custom_parts_output, last_updated
                    ) VALUES (
                        :applicator_id, :val, :val, :val,
                        :val, :val, :val, :val,
                        :val, :custom_json, CURRENT_TIMESTAMP
                    )
                    ON DUPLICATE KEY UPDATE
                        total_output = total_output + :val,
                        wire_crimper_output = wire_crimper_output + :val,
                        wire_anvil_output = wire_anvil_output + :val,
                        insulation_crimper_output = insulation_crimper_output + :val,
                        cutter_a_output = cutter_a_output + :val,
                        cutter_b_output = cutter_b_output + :val,
                        custom_parts_output = :custom_json,
                        last_updated = CURRENT_TIMESTAMP
                ");
                break;

            default:
                return "Invalid applicator type: " . htmlspecialchars($type, ENT_QUOTES);
        }

        // Bind parameters
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':val', $applicator_output, PDO::PARAM_INT);
        $stmt->bindParam(':custom_json', $custom_parts_json, PDO::PARAM_STR);

        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        error_log("DB Error in monitorApplicatorOutput(): " . $e->getMessage());
        return "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
