<?php
/*
    This file handles the UPDATE operation for applicator outputs.
    It updates existing records or inserts new ones in the `applicator_outputs` table.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/read_custom_parts.php';

function updateApplicatorOutput($applicator_data, $applicator_output, $record_id, $prev_applicator_data) {
    /*
        Update or insert applicator output data in the database.

        Args:
        - $applicator_data: array, applicator info from database
        - $applicator_output: int, value to set for all components
        - $record_id: int, ID of the associated record
        - $prev_applicator_data: array|null, previous applicator info for update

        Returns:
        - true on success
        - string with error message on failure
    */
    
    global $pdo;
    
    try {
        // Get the applicator type and ID
        $type = trim($applicator_data['description']);
        $applicator_id = $applicator_data['applicator_id'];
        $prev_applicator_id = $prev_applicator_data ? $prev_applicator_data['applicator_id'] : null;

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
        $exists = false; // Initialize exists variable
        
        switch (true) {
            case $type === "SIDE":
                // Check if we have a previous applicator ID to update
                if ($prev_applicator_id) {
                    // First, check if the applicator output record exists
                    $check_stmt = $pdo->prepare("
                        SELECT COUNT(*) FROM applicator_outputs 
                        WHERE record_id = :record_id AND applicator_id = :prev_applicator_id
                    ");
                    $check_stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
                    $check_stmt->bindParam(':prev_applicator_id', $prev_applicator_id, PDO::PARAM_INT);
                    $check_stmt->execute();
                    $exists = $check_stmt->fetchColumn() > 0;
                } else {
                    // No previous applicator ID, so we'll insert a new record
                    $exists = false;
                }

                if ($exists) {
                    // Update existing record
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
                } else {
                    // Insert new record
                    $stmt = $pdo->prepare("
                        INSERT INTO applicator_outputs 
                        (record_id, applicator_id, total_output, wire_crimper, wire_anvil, 
                        insulation_crimper, insulation_anvil, slide_cutter, cutter_holder, custom_parts) 
                        VALUES 
                        (:record_id, :applicator_id, :output_value, :output_value, :output_value, 
                        :output_value, :output_value, :output_value, :output_value, :custom_parts)
                    ");
                }
                break;

            case in_array($type, ["END", "CLAMP", "STRIP AND CRIMP"], true):
                // Check if we have a previous applicator ID to update
                if ($prev_applicator_id) {
                    // First, check if the applicator output record exists
                    $check_stmt = $pdo->prepare("
                        SELECT COUNT(*) FROM applicator_outputs 
                        WHERE record_id = :record_id AND applicator_id = :prev_applicator_id
                    ");
                    $check_stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
                    $check_stmt->bindParam(':prev_applicator_id', $prev_applicator_id, PDO::PARAM_INT);
                    $check_stmt->execute();
                    $exists = $check_stmt->fetchColumn() > 0;
                } else {
                    // No previous applicator ID, so we'll insert a new record
                    $exists = false;
                }

                if ($exists) {
                    // Update existing record
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
                } else {
                    // Insert new record
                    $stmt = $pdo->prepare("
                        INSERT INTO applicator_outputs 
                        (record_id, applicator_id, total_output, wire_crimper, wire_anvil, 
                        insulation_crimper, insulation_anvil, shear_blade, cutter_a, cutter_b, custom_parts) 
                        VALUES 
                        (:record_id, :applicator_id, :output_value, :output_value, :output_value, 
                        :output_value, :output_value, :output_value, :output_value, :output_value, :custom_parts)
                    ");
                }
                break;

            default:
                return "Invalid applicator type: " . htmlspecialchars($type, ENT_QUOTES);
        }

        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':output_value', $applicator_output, PDO::PARAM_INT);
        $stmt->bindParam(':custom_parts', $custom_parts_json, PDO::PARAM_STR);
        
        // Only bind prev_applicator_id for UPDATE operations when it's not null
        if ($exists && $prev_applicator_id) {
            $stmt->bindParam(':prev_applicator_id', $prev_applicator_id, PDO::PARAM_INT);
        }

        // Execute the query
        $stmt->execute();
        
        return true;
        
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateApplicatorOutput: " . $e->getMessage());
        return "A database error occurred while updating applicator output. Please try again later.";
    }
}


function disableApplicatorOutputs($applicator_id) {
    /*
        Disable (soft delete) applicator outputs in the database.
        Sets the status of all outputs for a given applicator to 'disabled'.

        Args:
        - $applicator_id: int, ID of the applicator to disable outputs for

        Returns:
        - true on successful disable
        - string containing error message on failure
    */

    global $pdo;

    try {
        // Update the status of all outputs for the given applicator
        $stmt = $pdo->prepare("
            UPDATE applicator_outputs
            SET is_active = 0
            WHERE applicator_id = :applicator_id
        ");

        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in disableApplicatorOutputs: " . $e->getMessage());
        return "A database error occurred while disabling applicator outputs. Please try again later.";
    }
}


function restoreApplicatorOutputs($applicator_id) {
    /*
        Restore (undo soft delete) applicator outputs in the database.
        Sets the status of all outputs for a given applicator to 'enabled'.

        Args:
        - $applicator_id: int, ID of the applicator to restore outputs for

        Returns:
        - true on successful restore
        - string containing error message on failure
    */

    global $pdo;

    try {
        // Update the status of all outputs for the given applicator
        $stmt = $pdo->prepare("
            UPDATE applicator_outputs
            SET is_active = 1
            WHERE applicator_id = :applicator_id
        ");

        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in restoreApplicatorOutputs: " . $e->getMessage());
        return "A database error occurred while restoring applicator outputs. Please try again later.";
    }
}


function restoreApplicatorOutputByRecordID($record_id): bool|string {
    /*
        Restore (undo soft delete) applicator outputs by record_id.

        Args:
        - $record_id: ID of the record to restore

        Returns:
        - true on success
        - string containing error message on failure
    */

    global $pdo;

    try {
        $stmt = $pdo->prepare("
            UPDATE applicator_outputs
            SET is_active = 1
            WHERE record_id = :record_id
        ");
        $stmt->bindValue(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Database Error in restoreApplicatorOutputByRecordID(): " . $e->getMessage());
        return "A database error occurred while restoring applicator outputs. Please try again later.";
    }
}