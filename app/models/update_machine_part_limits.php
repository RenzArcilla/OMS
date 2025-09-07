<?php 
/*
    This file defines a function to UPDATE entries in the machine_part_limits table.
    Used to set or update the maximum output limits for machine parts.
*/

function updateMachinePartLimits($machine_id, $part, $limit) {
    /*
        Function to update or insert machine part limits.

        Args:
        - $machine_id - int pertaining to an machine in the machines table
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
            INSERT INTO machine_part_limits (machine_id, machine_part, part_limit)
            VALUES (:machine_id, :machine_part, :part_limit)
            ON DUPLICATE KEY UPDATE part_limit = :part_limit
        ");


        // Bind parameters
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->bindParam(':machine_part', $part, PDO::PARAM_STR);
        $stmt->bindParam(':part_limit', $limit, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
        
        return true; // Success

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateMachinePartLimits: " . $e->getMessage());
        return "Failed to update machine part limits. Please try again.";
    }
}