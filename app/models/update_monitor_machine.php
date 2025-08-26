<?php
/*
    Contains functions to monitor, reset, and edit machine outputs.
    Handles standard columns and custom JSON parts for each machine.
*/

require_once __DIR__ . '/../includes/db.php';

function monitorMachineOutput($machine_data, $machine_output, $operation = 'increment') {
    /*
        Updates or inserts machine monitoring data.
        Supports incrementing or decrementing standard and custom outputs.

        Args:
        - $machine_data: array|int, machine info array or just machine_id
        - $machine_output: int, output value to adjust
        - $operation: string, "increment" or "decrement" (default "increment")

        Returns:
        - true on success
        - string with error message on failure
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


function resetMachinePartOutput($machine_id, $part_name) {
    /*
        Resets output for a specific machine part.
        Works for defined DB columns and custom JSON parts.

        Args:
        - $machine_id: int, ID of the machine
        - $part_name: string, name of the part to reset

        Returns:
        - true on success
        - string with error message on failure
    */

    global $pdo;
    
    $accepted_part_names = [
        'cut_blade_output',
        'strip_blade_a_output',
        'strip_blade_b_output'
    ];
    
    // Get custom machine parts 
    require_once "read_custom_parts.php";
    $custom_parts = getCustomParts("MACHINE");

    if (is_string($custom_parts)) {
        return $custom_parts; // error message
    }

    $custom_part_names = array_column($custom_parts, "part_name");

    // Case 1: part is a defined DB column
    if (in_array($part_name, $accepted_part_names, true)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE monitor_machine
                SET $part_name = 0
                WHERE machine_id = :machine_id
            ");
            $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            error_log("Database Error in resetMachinePartOutput: " . $e->getMessage());
            return "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    // Case 2: part is a custom JSON field
    if (in_array($part_name, $custom_part_names, true)) {
        try {
            // Fetch current JSON
            $stmt = $pdo->prepare("
                SELECT custom_parts_output
                FROM monitor_machine
                WHERE machine_id = :machine_id
                LIMIT 1
            ");
            $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) return "Machine not found";

            $decoded = json_decode($row["custom_parts_output"], true) ?: [];

            // Reset just the requested part
            if (isset($decoded[$part_name])) {
                $decoded[$part_name] = 0;
            }

            // Save back JSON
            $updatedJson = empty($decoded) ? null : json_encode((object) $decoded);
            $updateStmt = $pdo->prepare("
                UPDATE monitor_machine
                SET custom_parts_output = :json
                WHERE machine_id = :machine_id
            ");

            $updateStmt->bindValue(':json', $updatedJson, is_null($updatedJson) ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $updateStmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
            $updateStmt->execute();

            return true;

        } catch (PDOException $e) {
            error_log("Database Error in resetMachinePartOutput (custom): " . $e->getMessage());
            return "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    return "Reset cancelled: invalid part name!";
}


function editMachinePartOutputValue($machine_id, $part_name, $value) {
    /*
        Reverts a machine part output to a given value.
        Supports both standard columns and custom JSON parts.

        Args:
        - $machine_id: int, ID of the machine
        - $part_name: string, name of the part
        - $value: int, value to set for the part

        Returns:
        - true on success
        - string with error message on failure
    */

    global $pdo;
    
    $defined_parts = [
        'cut_blade_output',
        'strip_blade_a_output',
        'strip_blade_b_output'
    ];
    
    // Get custom machine parts 
    require_once "read_custom_parts.php";
    $custom_parts = getCustomParts("MACHINE");

    if (is_string($custom_parts)) {
        return $custom_parts; // error message
    }

    $custom_part_names = array_column($custom_parts, "part_name");

    // Case 1: part is a defined DB column
    if (in_array($part_name, $defined_parts, true)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE monitor_machine
                SET $part_name = :value
                WHERE machine_id = :machine_id
            ");

            $stmt->bindParam(':value', $value, PDO::PARAM_INT);
            $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            error_log("Database Error in editPaeditMachinePartOutputValuertOutputValue (defined): " . $e->getMessage());
            return "Database error in editMachinePartOutputValue (defined): " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    // Case 2: part is a custom JSON field
    if (in_array($part_name, $custom_part_names, true)) {
        try {
            // Fetch current JSON
            $stmt = $pdo->prepare("
                SELECT custom_parts_output
                FROM monitor_machine
                WHERE machine_id = :machine_id
                LIMIT 1
            ");
            $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) return "Machine not found";

            $decoded = json_decode($row["custom_parts_output"], true) ?: [];

            // Reset just the requested part
            if (!array_key_exists($part_name, $decoded)) {
                return "Part not found in custom JSON!";
            }
            $decoded[$part_name] = $value;

            // Save back JSON
            $updatedJson = empty($decoded) ? null : json_encode((object) $decoded);
            $updateStmt = $pdo->prepare("
                UPDATE monitor_machine
                SET custom_parts_output = :json
                WHERE machine_id = :machine_id
            ");
            
            $updateStmt->bindValue(':json', $updatedJson, is_null($updatedJson) ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $updateStmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
            $updateStmt->execute();

            return true;

        } catch (PDOException $e) {
            error_log("Database Error in editMachinePartOutputValue (custom): " . $e->getMessage());
            return "Database error in editMachinePartOutputValue (custom): " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
        }
    }

    return "Revert cancelled: invalid part name!";
}
