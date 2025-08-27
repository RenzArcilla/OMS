<?php
/*
    This file contains a function for deleting (hard DELETE-ing) a custom part row 
    in the custom_parts table. 
    It is used in the custom parts listing with pagination (e.g., infinite scroll).
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function deleteCustomPart($part_id) {
    /*
        Delete a custom part from the database.

        Args:
        - $part_id: ID of the custom part to delete

        Returns:
        - true on success
        - string containing error message on failure
    */
    global $pdo;

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("DELETE FROM custom_part_definitions WHERE part_id = :part_id");
        $stmt->bindParam(':part_id', $part_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Log error
        error_log("Database Error on deleteCustomPart: " . $e->getMessage());
        return "Database Error on deleteCustomPart: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
