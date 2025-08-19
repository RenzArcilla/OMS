<?php
/*
    This script handles the UPDATE operation for the applicator_reset in the database.
    It includes a function to update applicator reset data in the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateApplicatorReset($applicator_id, $part_reset, $reset_time, 
                        $undone_by) {
    /*
    Function to update applicator reset data in the database.

    Args:
    - $applicator_id: int pertaining to an applicator.
    - $part_reset: name of part that was reset.
    - $reset_time: timestamp of reset.
    - $undone_by: user_id pertaining to who reverted the reset.

    Returns:
    - true on successful operation.
    - string containing error message.
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
