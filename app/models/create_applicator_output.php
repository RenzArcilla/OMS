<?php
/*
    This script handles the CREATE operation for submitting a new output record in the applicator_output database.
    It includes a function to insert output data into the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function submitApplicatorOutput($applicator_data, $app_output, $record_id) {
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
        // Convert app_output to integer
        $increment_value = intval($app_output);
        
        // Get the applicator type and ID
        $type = $applicator_data['description'];
        $applicator_id = $applicator_data['applicator_id'];
        
        // Always create a new record with all values set to the increment value
        switch ($type) {
            case "SIDE":
                $stmt = $pdo->prepare("
                    INSERT INTO applicator_outputs 
                    (record_id, applicator_id, total_output, wire_crimper, wire_anvil, 
                     insulation_crimper, insulation_anvil, slide_cutter, cutter_holder) 
                    VALUES 
                    (:record_id, :applicator_id, :increment, :increment, :increment, 
                     :increment, :increment, :increment, :increment)
                ");
                break;
                
            case "END":
                $stmt = $pdo->prepare("
                    INSERT INTO applicator_outputs 
                    (record_id, applicator_id, total_output, wire_crimper, wire_anvil, 
                     insulation_crimper, insulation_anvil, shear_blade, cutter_a, cutter_b) 
                    VALUES 
                    (:record_id, :applicator_id, :increment, :increment, :increment, 
                     :increment, :increment, :increment, :increment, :increment)
                ");
                break;
                
            default:
                return "<script>
                    alert('Invalid applicator type: " . htmlspecialchars($type, ENT_QUOTES) . "');
                    window.location.href = '../views/record_output.php';</script>";
        }
        
        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':increment', $increment_value, PDO::PARAM_INT);
        
        // Execute the query
        $stmt->execute();
        
        return true;
        
    } catch (PDOException $e) {
        // Log error and return error message
        error_log("Database Error in submitApplicatorOutput: " . $e->getMessage());
        return "<script>
            alert('Database error occurred while submitting applicator output: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "');
            window.location.href = '../views/record_output.php';</script>";
    }
}