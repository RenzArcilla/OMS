<?php
/*
    This script handles the CREATE operation for submitting a new output record in the machine_outputs database.
    It includes a function to insert machine output data into the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function submitMachineOutput($machine_data, $machine_output, $record_id) {
    /*
    Function to submit machine output data to the database.
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
        // Convert machine_output to integer
        $output_value = intval($machine_output);
        
        // Get the machine ID
        $machine_id = $machine_data['machine_id'];
        
        // Always create a new record with all values set to the output value
        $stmt = $pdo->prepare("
            INSERT INTO machine_outputs 
            (record_id, machine_id, total_machine_output, cut_blade, strip_blade_a, strip_blade_b) 
            VALUES 
            (:record_id, :machine_id, :output_value, :output_value, :output_value, :output_value)
        ");
        
        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->bindParam(':output_value', $output_value, PDO::PARAM_INT);
        
        // Execute the query
        $stmt->execute();
        
        return true;
        
    } catch (PDOException $e) {
        // Log error and return error message
        error_log("Database Error in submitMachineOutput: " . $e->getMessage());
        return "<script>
            alert('Database error occurred while submitting machine output: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "');
            window.location.href = '../templates/record_output.php';</script>";
    }
}
?>