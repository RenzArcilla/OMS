<?php
/*
    This file handles the UPDATE operation for applicator resets.
    Updates an applicator_reset record by marking it undone with a user and timestamp.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateApplicatorReset($applicator_id, $part_reset, $reset_time, 
                        $undone_by) {
    /*
        Update an applicator_reset record to mark it as undone.

        Args:
        - $applicator_id: int, ID of the applicator
        - $part_reset: string, name of the part that was reset
        - $reset_time: string, timestamp of the original reset
        - $undone_by: int, user ID who reverted the reset

        Returns:
        - true on successful update
        - string containing error message if failed
    */

    global $pdo;
    
    try {
        // Prepare SQL update query (fixed: removed trailing comma after undone_time)
        $stmt = $pdo->prepare("
            UPDATE applicator_reset
            SET
                undone_by = :undone_by,
                undone_time = NOW()
            WHERE applicator_id = :applicator_id
                AND part_reset = :part_reset
                AND reset_time = :reset_time
        ");

        // Bind parameters
        $stmt->bindParam(':undone_by', $undone_by, PDO::PARAM_INT);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
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
        error_log("Database Error in updateApplicatorReset: " . $e->getMessage());
        return "Database error in updateApplicatorReset: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
