<?php
/*
    This script handles the UPDATE operation for updating an output record in the machine_outputs table in the db.
    It includes a function to insert machine output data into the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateMachineOutput($machine_data, $machine_output, $record_id) {
    /*
    Function to update machine output data to the database.
    The machine_output value is used to set all component values for this machine.
    
    Args:
    - $machine_data: Array containing machine information from database
    - $machine_output: Integer value to set for all component values
    - $record_id: ID of the record to associate this output with
    
    Returns:
    - Boolean true on success
    - String containing error message and redirect using JS <alert> on failure
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