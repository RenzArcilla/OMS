<?php
/*
    This script handles the UPDATE operation for updating an output record in the applicator_outputs table in the db.
    It includes a function to update an applicator output data into the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/read_custom_parts.php';

function updateApplicatorOutput($applicator_data, $applicator_output, $record_id, $prev_applicator_data) {
    /*
    Function to update applicator output data in the database.
    The applicator_output value is added to all existing component values for this applicator.
    
    Args:
    - $applicator_data: Array containing applicator information from database
    - $applicator_output: Integer value to add to all existing component values
    - $record_id: ID of the record to associate this output with
    
    Returns:
    - Boolean true on success
    - String containing error message and redirect using JS <alert> on failure
    */
    
    global $pdo;
    
    try {
        // Get the applicator type and ID
        $type = trim($applicator_data['description']);
        $applicator_id = $applicator_data['applicator_id'];
        $prev_applicator_id = $prev_applicator_data['applicator_id'];

        // Get the custom parts of the applicator and ensure they are set to the output value
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
        
        // Create a new record with all values set to the applicator output value
        switch (true) {
            case $type === "SIDE":
                $stmt = $pdo->prepare("
                    UPDATE applicator_outputs
                    SET
                        applicator_id = :applicator_id,
                        total_output = :output_value,
                        wire_crimper = :output_value,
                        wire_anvil = :output_value,
                        insulation_crimper = :output_value,
                        insulation_anvil = :output_value,
                        slide_cutter = :output_value,
                        cutter_holder = :output_value,
                        custom_parts = :custom_parts
                    WHERE record_id = :record_id
                        AND applicator_id = :prev_applicator_id
                ");
                break;

            case in_array($type, ["END", "CLAMP", "STRIP AND CRIMP"], true):
                $stmt = $pdo->prepare("
                    UPDATE applicator_outputs
                    SET
                        applicator_id = :applicator_id,
                        total_output = :output_value,
                        wire_crimper = :output_value,
                        wire_anvil = :output_value,
                        insulation_crimper = :output_value,
                        insulation_anvil = :output_value,
                        shear_blade = :output_value,
                        cutter_a = :output_value,
                        cutter_b = :output_value,
                        custom_parts = :custom_parts
                    WHERE record_id = :record_id
                        AND applicator_id = :prev_applicator_id
                ");
                break;

            default:
                return "Invalid applicator type: " . htmlspecialchars($type, ENT_QUOTES);
        }

        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':prev_applicator_id', $prev_applicator_id, PDO::PARAM_INT);
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