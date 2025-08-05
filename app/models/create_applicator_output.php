<?php
/*
    This script handles the CREATE operation for submitting a new output record in the applicator_output database.
    It includes a function to insert output data into the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function submitApplicatorOutput($applicator_data, $applicator_output, $record_id) {
    /*
    Function to submit applicator output data to the database.
    The app_output value is added to all existing component values for this applicator.
    
    Args:
    - $applicator_data: Array containing applicator information from database
    - $app_output: Integer value to add to all existing component values
    - $record_id: ID of the record to associate this output with
    
    Returns:
    - Boolean true on success
    - String containing error message and redirect using JS <alert> on failure
    */
    
    global $pdo;
    
    try {
        // Get the applicator type and ID
        $type = $applicator_data['description'];
        $applicator_id = is_array($applicator_data) ? $applicator_data['applicator_id'] : $applicator_data;

        // Get the custom parts of the applicator and ensure they are set to the output value
        require_once __DIR__ . '/read_custom_parts.php';
        $custom_applicator_parts = getCustomParts('APPLICATOR');

        if (is_string($custom_applicator_parts)) {
            // If getCustomParts returned an error 
            return $custom_applicator_parts;
        }

                $custom_parts_arr = []; // Default to empty

        if (!empty($custom_applicator_parts)) {
            // Convert each custom part to the desired format
            $json_array = [];
            foreach ($custom_applicator_parts as $part) {
                $json_array[] = [
                    'name' => $part['part_name'],
                    'value' => $applicator_output
                ];
            }
            $custom_parts_arr = $json_array;
        }

        // Convert to JSON format  
        $custom_parts_json = json_encode($custom_parts_arr);
        
        // Always create a new record with all values set to the applicator output value
        switch ($type) {
            case "SIDE":
                $stmt = $pdo->prepare("
                    INSERT INTO applicator_outputs 
                    (record_id, applicator_id, total_output, wire_crimper, wire_anvil, 
                    insulation_crimper, insulation_anvil, slide_cutter, cutter_holder, custom_parts) 
                    VALUES 
                    (:record_id, :applicator_id, :output_value, :output_value, :output_value, 
                    :output_value, :output_value, :output_value, :output_value, :custom_parts)
                ");
                break;
                
            case "END":
                $stmt = $pdo->prepare("
                    INSERT INTO applicator_outputs 
                    (record_id, applicator_id, total_output, wire_crimper, wire_anvil, 
                    insulation_crimper, insulation_anvil, shear_blade, cutter_a, cutter_b, custom_parts) 
                    VALUES 
                    (:record_id, :applicator_id, :output_value, :output_value, :output_value, 
                    :output_value, :output_value, :output_value, :output_value, :output_value, :custom_parts)
                ");
                break;
                
            default:
                return "Invalid applicator type: " . htmlspecialchars($type, ENT_QUOTES);
        }
        
        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':output_value', $applicator_output, PDO::PARAM_INT);
        $stmt->bindParam(':custom_parts', $custom_parts_json, PDO::PARAM_STR);

        
        // Execute the query
        $stmt->execute();
        
        return true;
        
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}