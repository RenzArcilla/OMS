<?php
/*
    This file contains functions for deleting and disabling a machine row 
    in the machines table. 
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function disableMachine($machine_id): bool {
    /*
        Disable (soft delete) a machine in the database.

        Args:
        - $machine_id: ID of the machine to disable

        Returns:
        - true on success
        - string containing error message on failure
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
