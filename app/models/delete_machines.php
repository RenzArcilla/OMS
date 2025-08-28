<?php
/*
    This file contains a function for disabling (soft DELETE-ing) a machine row 
    in the machines table. 
    It is used in the machine listing with pagination (e.g., infinite scroll).
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


function deleteMachine($machine_id): bool|string {
    /*
        Delete a machine from the database.

        Args:
        - $machine_id: ID of the machine to delete

        Returns:
        - true on success
        - string containing error message on failure
    */
    global $pdo;

    // Basic validation
    if (!is_numeric($machine_id) || $machine_id <= 0) {
        return "Invalid machine ID.";
    }

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("DELETE FROM machines WHERE machine_id = :machine_id");
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Check if any row was deleted
        if ($stmt->rowCount() === 0) {
            return "No machine found with the given ID.";
        }

        return true;
    
    } catch (PDOException $e) {
        // Log error
        error_log("Database Error on deleteMachine: " . $e->getMessage());
        return "Database error while deleting machine: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}