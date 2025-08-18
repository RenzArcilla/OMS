<?php
/*
    This file contains functions that updates the monitoring data for applicators.
*/


function monitorApplicatorOutput($applicator_data, $applicator_output, $direction = "increment") {
    /*
        Updates or inserts applicator monitoring data into the `monitor_applicator` table.
        Automatically handles both SIDE-type and END/CLAMP/STRIP AND CRIMP-type applicators,
        including increment or decrement logic for outputs and updating custom parts.
    */

    global $pdo;

    try {
        // Extract type and ID (handles if $applicator_data is just an ID or full array)
        $type = $applicator_data['description'];
        $applicator_id = is_array($applicator_data) ? $applicator_data['applicator_id'] : $applicator_data;

        // Convert to negative if decrementing
        if (strtolower($direction) === "decrement") {
            $applicator_output = -abs($applicator_output);
        }

        // Load custom parts for applicators
        require_once __DIR__ . '/read_custom_parts.php';
        $custom_parts = getCustomParts('APPLICATOR');

        // If custom parts retrieval failed (string message returned), pass it back
        if (is_string($custom_parts)) {
            return $custom_parts; 
        }

        // Build an array of part => output
        $new_parts = [];
        foreach ($custom_parts as $part) {
            $new_parts[$part['part_name']] = $applicator_output;
        }

        // Check if there’s an existing record for this applicator
        $stmt_check = $pdo->prepare("
            SELECT custom_parts_output 
            FROM monitor_applicator 
            WHERE applicator_id = :id 
            LIMIT 1
        ");
        $stmt_check->execute([':id' => $applicator_id]);
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

        // Prepare SQL depending on applicator type
        switch (true) {
            // SIDE-type applicators
            case $type === "SIDE":
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

            // END, CLAMP, and STRIP AND CRIMP-type applicators
            case in_array($type, ["END", "CLAMP", "STRIP AND CRIMP"], true):
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

            // Unknown applicator type
            default:
                return "Invalid applicator type: " . htmlspecialchars($type, ENT_QUOTES);
        }

        // Bind parameters
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':val', $applicator_output, PDO::PARAM_INT);
        $stmt->bindParam(':custom_json', $custom_parts_json, PDO::PARAM_STR);

        // Execute query
        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        // Log and return a sanitized error
        error_log("DB Error in monitorApplicatorOutput(): " . $e->getMessage());
        return "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}



function resetApplicatorPartOutput($applicator_id, $part_name) {
    /*
        Resets the output for a specific part of an applicator in the monitor_applicator table.
        Handles both defined (columns) and custom (JSON) parts.

        Returns:
        - true on success 
        - error string
    */

    global $pdo;
    
    $defined_parts = [
        'wire_crimper_output',
        'wire_anvil_output',
        'insulation_crimper_output',
        'insulation_anvil_output',
        'slide_cutter_output',
        'cutter_holder_output',
        'shear_blade_output',
        'cutter_a_output',
        'cutter_b_output'
    ];
    
    // Get custom applicator parts 
    require_once "read_custom_parts.php";
    $custom_parts = getCustomParts("APPLICATOR");

    if (is_string($custom_parts)) {
        return $custom_parts; // error message
    }

    $custom_part_names = array_column($custom_parts, "part_name");

    // Case 1: part is a defined DB column
    if (in_array($part_name, $defined_parts, true)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE monitor_applicator
                SET $part_name = 0
                WHERE applicator_id = :applicator_id
            ");
            $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            error_log("Database Error in resetApplicatorPartOutput: " . $e->getMessage());
            return "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    // Case 2: part is a custom JSON field
    if (in_array($part_name, $custom_part_names, true)) {
        try {
            // Fetch current JSON
            $stmt = $pdo->prepare("
                SELECT custom_parts_output
                FROM monitor_applicator
                WHERE applicator_id = :applicator_id
                LIMIT 1
            ");
            $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) return "Applicator not found";

            $decoded = json_decode($row["custom_parts_output"], true) ?: [];

            // Reset just the requested part
            if (isset($decoded[$part_name])) {
                $decoded[$part_name] = 0;
            }

            // Save back JSON
            $updatedJson = empty($decoded) ? null : json_encode((object) $decoded);
            $updateStmt = $pdo->prepare("
                UPDATE monitor_applicator
                SET custom_parts_output = :json
                WHERE applicator_id = :applicator_id
            ");

            $updateStmt->bindValue(':json', $updatedJson, is_null($updatedJson) ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $updateStmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
            $updateStmt->execute();

            return true;

        } catch (PDOException $e) {
            error_log("Database Error in resetApplicatorPartOutput (custom): " . $e->getMessage());
            return "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    return "Reset cancelled: invalid part name!";
}


function editPartOutputValue($applicator_id, $part_name, $value) {
    /*
    Reverts the output to previous value for a specific part of an applicator in the monitor_applicator table.
    Handles both defined (columns) and custom (JSON) parts.

    Parameters:
    - $applicator_id: int, pertains to an applicator
    - $part_name: str, name of the part to revert output (defined/custom)
    - $value: int, value to revert back to 

    Returns:
    - true on success 
    - error string
    */

    global $pdo;
    
    $defined_parts = [
        'wire_crimper_output',
        'wire_anvil_output',
        'insulation_crimper_output',
        'insulation_anvil_output',
        'slide_cutter_output',
        'cutter_holder_output',
        'shear_blade_output',
        'cutter_a_output',
        'cutter_b_output'
    ];
    
    // Get custom applicator parts 
    require_once "read_custom_parts.php";
    $custom_parts = getCustomParts("APPLICATOR");

    if (is_string($custom_parts)) {
        return $custom_parts; // error message
    }

    $custom_part_names = array_column($custom_parts, "part_name");

    // Case 1: part is a defined DB column
    if (in_array($part_name, $defined_parts, true)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE monitor_applicator
                SET $part_name = :value
                WHERE applicator_id = :applicator_id
            ");

            $stmt->bindParam(':value', $value, PDO::PARAM_INT);
            $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            error_log("Database Error in editPartOutputValue (defined): " . $e->getMessage());
            return "Database error in editPartOutputValue (defined): " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    // Case 2: part is a custom JSON field
    if (in_array($part_name, $custom_part_names, true)) {
        try {
            // Fetch current JSON
            $stmt = $pdo->prepare("
                SELECT custom_parts_output
                FROM monitor_applicator
                WHERE applicator_id = :applicator_id
                LIMIT 1
            ");
            $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) return "Applicator not found";

            $decoded = json_decode($row["custom_parts_output"], true) ?: [];

            // Reset just the requested part
            if (!array_key_exists($part_name, $decoded)) {
                return "Part not found in custom JSON!";
            }
            $decoded[$part_name] = $value;

            // Save back JSON
            $updatedJson = empty($decoded) ? null : json_encode((object) $decoded);
            $updateStmt = $pdo->prepare("
                UPDATE monitor_applicator
                SET custom_parts_output = :json
                WHERE applicator_id = :applicator_id
            ");
            
            $updateStmt->bindValue(':json', $updatedJson, is_null($updatedJson) ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $updateStmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
            $updateStmt->execute();

            return true;

        } catch (PDOException $e) {
            error_log("Database Error in editPartOutputValue (custom): " . $e->getMessage());
            return "Database error in editPartOutputValue (custom): " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    return "Revert cancelled: invalid part name!";
}
