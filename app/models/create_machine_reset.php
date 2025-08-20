<?php 
/*
    This file defines a function to CREATE entries in the machine_reset table.
    Used to log part resets performed on machines.
*/


function createMachineReset($user_id, $machine_id, $part_reset, $previous_value) {
    /*
        Function to record machine_part resets.

        Args:
        - $user_id - int pertaining to a user in the users table
        - $machine_id - int pertaining to a machine in the users machines table
        - $part_reset - part name to reset
        - $previous_value - previous total value before reset

        Returns:
        - true on success
        - string containing error message
    */

    global $pdo;

    // Main logic
    try {
        $stmt = $pdo->prepare("
            INSERT INTO machine_reset (machine_id, reset_by, part_reset, 
                    previous_value, reset_time)
            VALUES (:machine_id, :reset_by, :part_reset, 
                    :previous_value, now())
        ");

        // Bind parameters
        $stmt->bindParam(':machine_id', $machine_id);
        $stmt->bindParam(':reset_by', $user_id);
        $stmt->bindParam(':part_reset', $part_reset);
        $stmt->bindParam(':previous_value', $previous_value);

        // Execute the statement
        $stmt->execute();
        
        return true; // Success

    
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in createMachineReset: " . $e->getMessage());
        return "Database error in createMachineReset: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}