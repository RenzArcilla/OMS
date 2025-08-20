<?php
/*
    This file defines a function that retrieves the output of an applicator part,
    whether it is a fixed schema part or a custom part stored as JSON.
*/

require_once __DIR__ . '/../includes/db.php';
require_once "read_custom_parts.php";

function getMonitorApplicatorPartOutput($applicator_id, $part_name) {
    /*
        Retrieve the integer output of a specific applicator part.

        Handles both predefined parts (direct columns in monitor_applicator table)
        and custom parts (stored in JSON in custom_parts_output column).

        Args:
        - $applicator_id: int, ID of the applicator
        - $part_name: string, name of the applicator part

        Returns:
        - int: output value of the part
        - string: error message if part not found or DB error occurs
    */

    global $pdo;

    // Fixed schema parts (direct DB columns)
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

    // Load dynamic custom parts
    $custom_applicator_parts = getCustomParts("APPLICATOR");
    if (is_string($custom_applicator_parts)) {
        return $custom_applicator_parts; // error string
    }
    $custom_parts = array_column($custom_applicator_parts, "part_name");

    // Decide query source
    if (in_array($part_name, $defined_parts)) {
        // Direct column for defined parts
        $query = "SELECT $part_name FROM monitor_applicator WHERE applicator_id = :applicator_id";
    } elseif (in_array($part_name, $custom_parts)) {
        // Custom parts stored in JSON
        $query = "SELECT custom_parts_output FROM monitor_applicator WHERE applicator_id = :applicator_id";
    } else {
        return "Invalid part name!"; 
    }

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) return "Monitor applicator entry does not exist.";

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
        error_log("DB Error in getMonitorApplicatorPartOutput: " . $e->getMessage());
        return "Database error in getMonitorApplicatorPartOutput: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
