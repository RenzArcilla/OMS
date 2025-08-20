<?php 
/*
    This file defines a function to CREATE entries in the applicator_reset table.
    Used to log part resets performed on applicators.
*/


function createApplicatorReset($user_id, $applicator_id, $part_reset, $previous_value) {
    /*
        Function to record applicator_part resets.

        Args:
        - $user_id - int pertaining to a user in the users table
        - $applicator_id - int pertaining to a applicator in the users applicators table
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
            INSERT INTO applicator_reset (applicator_id, reset_by, part_reset, 
                    previous_value, reset_time)
            VALUES (:applicator_id, :reset_by, :part_reset, 
                    :previous_value, now())
        ");

        // Bind parameters
        $stmt->bindParam(':applicator_id', $applicator_id);
        $stmt->bindParam(':reset_by', $user_id);
        $stmt->bindParam(':part_reset', $part_reset);
        $stmt->bindParam(':previous_value', $previous_value);

        // Execute the statement
        $stmt->execute();
        
        return true; // Success

    
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in createApplicatorReset: " . $e->getMessage());
        return "Database error in createApplicatorReset: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}