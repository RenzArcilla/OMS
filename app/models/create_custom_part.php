<?php
/*
    This file defines a function to CREATE custom part entries in the database.
    Handles inserting custom part details into the custom_parts table.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function createCustomPart($user_id, $type, $name) {
    /*
        Function to create a new custom part in the database.

        Args:
        - $type: Type of the custom part.
        - $name: Name of the custom part.
        
        Returns:
        - true on successful operation.
        - string containing error message.
    */

    global $pdo;

    try {
        // Prepare SQL insert query
        $stmt = $pdo->prepare("
            INSERT INTO custom_part_definitions (equipment_type, part_name, created_by)
            VALUES (:type, :name, :user_id)
        ");

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "Failed to add custom part. Please try again.";
        }
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in createCustomPart: " . $e->getMessage());
        return "A database error occurred while creating custom part. Please try again later.";
    }
}