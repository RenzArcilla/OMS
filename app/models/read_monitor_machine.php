<?php
/*
    This file defines a function that retrieves the output of a machine part,
    whether it is a fixed schema part or a custom part stored as JSON.
*/

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/read_custom_parts.php';

function getMonitorMachinePartOutput($machine_id, $part_name) {
    /*
        Retrieve the integer output of a specific machine part.

        Handles both predefined parts (direct columns in monitor_machine table)
        and custom parts (stored in JSON in custom_parts_output column).

        Args:
        - $machine_id: int, ID of the machine
        - $part_name: string, name of the machine part

        Returns:
        - int: output value of the part
        - string: error message if part not found or DB error occurs
    */
        
    global $pdo;

    // Fixed schema parts (direct DB columns)
    $defined_parts = [
        'cut_blade_output',
        'strip_blade_a_output',
        'strip_blade_b_output'
    ];

    // Load dynamic custom parts
    $custom_machine_parts = getCustomParts("MACHINE");
    if (is_string($custom_machine_parts)) {
        return $custom_machine_parts; // error string
    }
    $custom_parts = array_column($custom_machine_parts, "part_name");

    // Decide query source
    if (in_array($part_name, $defined_parts)) {
        // Direct column for defined parts
        $query = "SELECT $part_name FROM monitor_machine WHERE machine_id = :machine_id";
    } elseif (in_array($part_name, $custom_parts)) {
        // Custom parts stored in JSON
        $query = "SELECT custom_parts_output FROM monitor_machine WHERE machine_id = :machine_id";
    } else {
        return "Invalid part name!"; 
    }

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) return "Monitor machine entry does not exist.";

        // Handle return value
        if (in_array($part_name, $defined_parts)) {
            // When part_name is a defined part
            return (int)$result[$part_name];
        } else {
            // When part_name is a custom part
            $decoded = json_decode($result["custom_parts_output"], true);
            if (json_last_error() !== JSON_ERROR_NONE) return "An error occurred during json decoding!";
            return isset($decoded[$part_name]) ? (int)$decoded[$part_name] : "Part value not found.";
        }
    } catch (PDOException $e) {
        error_log("DB Error in getMonitorMachinePartOutput: " . $e->getMessage());
        return "Database error in getMonitorMachinePartOutput: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
