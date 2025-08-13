<?php
/*
    This file defines a function that deletes an applicator output row in the database.
    Used in features like machine listing with pagination (e.g., infinite scroll).
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 


function deleteApplicatorOutputs($applicator_id, $record_id) {
    /*
    Function to delete an applicator output in the database.

    Args:
    - $applicator_id: ID of the applicator to delete.
    - $record_id: record ID of the applicator to delete
    
    Returns:
    - True on success, string containing error message on failure.
    */

    global $pdo;

    try {
        // Prepare the SQL DELETE statement
        $stmt = $pdo->prepare("
            DELETE FROM applicator_outputs
            WHERE applicator_id = :applicator_id
                AND record_id = :record_id
        ");
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Log error and return a sanitized error message
        error_log("Database Error in deleteApplicatorOutputs(): " . $e->getMessage());
        return "Database Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
