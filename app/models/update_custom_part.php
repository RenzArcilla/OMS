<?php 
/*
    This file handles the UPDATE operation for custom parts.
    Updates a custom_part record with new values.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateCustomPart($part_id, $name, $type) {
    /*
        Update a custom_part record with new values.

        Args:
        - $part_id: int, ID of the custom part
        - $name: string, new name of the custom part
        - $type: string, new type of the custom part

        Returns:
        - true on successful update
        - string containing error message if failed
    */

    global $pdo;
    
    try {
        // Prepare SQL update query (fixed: removed trailing comma after undone_time)
        $stmt = $pdo->prepare("
            UPDATE custom_part_definitions
            SET
                part_name = :part_name,
                equipment_type = :type
            WHERE part_id = :part_id
        ");

        // Bind parameters
        $stmt->bindParam(':part_name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':part_id', $part_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return "No matching custom part record found for update.";
        }

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateCustomPart: " . $e->getMessage());
        return "Database error occurred when updating custom part: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

