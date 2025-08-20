<?php
/*
    This file contains a function for disabling (soft DELETE-ing) a machine record 
    in the database. It is typically used in machine listings with pagination 
    (e.g., infinite scroll).
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function disableApplicator($applicator_id): bool {
    /*
        Function to disable an applicator in the database.

        Args:
        - $applicator_id: ID of the applicator to disable.

        Returns:
        - True on success, string containing error message on failure.
    */
    global $pdo;

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("UPDATE applicators 
                            SET is_active = 0 
                            WHERE applicator_id = :applicator_id");
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Log error
        error_log("Database Error on disableApplicator: " . $e->getMessage());
        return "Database Error on disableApplicator: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
