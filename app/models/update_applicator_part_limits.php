<?php 
/*
    This file defines a function to UPDATE entries in the applicator_part_limits table.
    Used to set or update the maximum output limits for applicator parts.
*/

function updateApplicatorPartLimits($applicator_id, $part, $limit) {
    /*
        Function to update or insert applicator part limits.

        Args:
        - $applicator_id - int pertaining to an applicator in the applicators table
        - $part - string representing the part name
        - $limit - float representing the part limit

        Returns:
        - true on success
        - string containing error message
    */

    global $pdo;

    // Main logic
    try {
        // Prepare the SQL statement for inserting or updating part limits
        $stmt = $pdo->prepare("
            INSERT INTO applicator_part_limits (applicator_id, applicator_part, part_limit)
            VALUES (:applicator_id, :applicator_part, :part_limit)
            ON DUPLICATE KEY UPDATE part_limit = :part_limit
        ");


        // Bind parameters
        $stmt->bindParam(':applicator_id', $applicator_id);
        $stmt->bindParam(':applicator_part', $part);
        $stmt->bindParam(':part_limit', $limit);

        // Execute the statement
        $stmt->execute();
        
        return true; // Success

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateApplicatorPartLimits: " . $e->getMessage());
        return "Database error in updateApplicatorPartLimits: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}