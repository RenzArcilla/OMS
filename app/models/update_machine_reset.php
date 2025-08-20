<?php
/*
    This file handles the UPDATE operation for machine resets.
    Updates an existing machine reset record in the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateMachineReset($machine_id, $part_reset, $reset_time, 
                        $undone_by) {
    /*
        Update machine reset data in the database.

        Args:
        - $machine_id: int, ID of the machine
        - $part_reset: string, name of part that was reset
        - $reset_time: string, timestamp of the reset
        - $undone_by: int, user_id of who reverted the reset

        Returns:
        - true on successful update
        - string containing error message on failure
    */

    global $pdo;
    
    try {
        // Prepare SQL update query (fixed: removed trailing comma after undone_time)
        $stmt = $pdo->prepare("
            UPDATE machine_reset
            SET
                undone_by = :undone_by,
                undone_time = NOW()
            WHERE machine_id = :machine_id
                AND part_reset = :part_reset
                AND reset_time = :reset_time
        ");

        // Bind parameters
        $stmt->bindParam(':undone_by', $undone_by, PDO::PARAM_INT);
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->bindParam(':part_reset', $part_reset, PDO::PARAM_STR);
        $stmt->bindParam(':reset_time', $reset_time, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return "No matching reset record found for update.";
        }

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateMachineReset: " . $e->getMessage());
        return "Database error in updateMachineReset: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
