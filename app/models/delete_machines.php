<?php
/*
    This file defines a function that disables a machine row in the database.
    Used in the machine listing with pagination, such as in infinite scroll.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function disableMachine($machine_id): bool {
    /*
    Function to disable a machine in the database.

    Args:
    - $machine_id: ID of the machine to disable.

    Returns:
    - True on success, string containing error message on failure.
    */
    global $pdo;

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("UPDATE machines SET is_active = 0 WHERE machine_id = :machine_id");
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Log error
        error_log("Database Error on disableMachine: " . $e->getMessage());
        return "Database Error on disableMachine: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
