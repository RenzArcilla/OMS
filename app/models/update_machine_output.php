<?php
/*
    This file handles the UPDATE operation for machine outputs.
    Updates an existing machine output record in the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateMachineOutput($machine_data, $machine_output, $record_id) {
    /*
        Update machine output data in the database.
        Sets all component values (defined + custom parts) to the given output value.

        Args:
        - $machine_data: array, machine information from database
        - $machine_output: int, value to set for all components
        - $record_id: int, ID of the record to update

        Returns:
        - true on successful update
        - string containing error message on failure
    */
    
    global $pdo;
    
    try {      
        // Get the machine ID
        $machine_id = $machine_data['machine_id'];

        // Get the custom parts of the machine and ensure they are set to the output value
            require_once __DIR__ . '/read_custom_parts.php';
            $custom_machine_parts = getCustomParts('MACHINE');

            if (is_string($custom_machine_parts)) { 
                // If getCustomParts returned an error 
                return $custom_machine_parts;
            }

            $custom_parts_arr = []; // Default to empty

            if (!empty($custom_machine_parts)) {
                // Convert each custom part to the desired format   
                $json_array = [];
                foreach ($custom_machine_parts as $part) {
                    $json_array[] = [
                        'name' => $part['part_name'],
                        'value' => $machine_output
                    ];
                }
                $custom_parts_arr = $json_array;
            }

            // Convert to JSON format
            $custom_parts_json = json_encode($custom_parts_arr); 

        // Create a new record with all values set to the output value
        $stmt = $pdo->prepare("
            UPDATE machine_outputs
            SET
                machine_id = :machine_id,
                total_machine_output = :output_value,
                cut_blade = :output_value,
                strip_blade_a = :output_value,
                strip_blade_b = :output_value,
                custom_parts = :custom_parts
            WHERE record_id = :record_id
        ");
        
        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->bindParam(':output_value', $machine_output, PDO::PARAM_INT);
        $stmt->bindParam(':custom_parts', $custom_parts_json, PDO::PARAM_STR);
        
        // Execute the query
        $stmt->execute();
        
        return true;
        
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateMachineOutput: " . $e->getMessage());
        return "Database error occurred in updateMachineOutput: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function disableMachineOutputs($machine_id) {
    /*
        Disable (soft delete) machine outputs in the database.
        Sets the status of all outputs for a given machine to 'disabled'.

        Args:
        - $machine_id: int, ID of the machine to disable outputs for

        Returns:
        - true on successful disable
        - string containing error message on failure
    */

    global $pdo;

    try {
        // Update the status of all outputs for the given machine
        $stmt = $pdo->prepare("
            UPDATE machine_outputs
            SET is_active = 0
            WHERE machine_id = :machine_id
        ");

        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in disableMachineOutputs: " . $e->getMessage());
        return "Database error occurred when disabling machine outputs: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function restoreMachineOutputs($machine_id) {
    /*
        Restore (undo soft delete) machine outputs in the database.
        Sets the status of all outputs for a given machine to 'enabled'.

        Args:
        - $machine_id: int, ID of the machine to restore outputs for

        Returns:
        - true on successful restore
        - string containing error message on failure
    */

    global $pdo;

    try {
        // Update the status of all outputs for the given machine
        $stmt = $pdo->prepare("
            UPDATE machine_outputs
            SET is_active = 1
            WHERE machine_id = :machine_id
        ");

        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in restoreMachineOutputs: " . $e->getMessage());
        return "Database error occurred when restoring machine outputs: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function restoreMachineOutputByRecordID($record_id): bool|string {
    /*
        Restore (undo soft delete) a single machine output by record_id.

        Args:
        - $record_id: ID of the record to restore

        Returns:
        - true on success
        - string containing error message on failure
    */

    global $pdo;

    try {
        $stmt = $pdo->prepare("
            UPDATE machine_outputs
            SET is_active = 1
            WHERE record_id = :record_id
        ");
        $stmt->bindValue(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Database Error in restoreMachineOutputByRecordID(): " . $e->getMessage());
        return "Database error occurred when restoring machine output: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}