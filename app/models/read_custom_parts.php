<?php
/*
    This file defines a function that queries a list of custom parts from the database.
    Used when recording outputs for machines or applicators.

    Function included:
    - getCustomParts($type): Fetches active custom parts for a given equipment type.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function getCustomParts($type){
    /*
        Function to fetch custom parts of machines/applicators.
        It prepares and executes a SELECT query that fetches custom parts of machines or applicators,
        and returns them as an associative array.

        Args:
        - $type: Type of custom parts to fetch (e.g., 'machine' or 'applicator').

        Returns:
        - Array of machines (associative arrays) on success.
        - String containing error message.
    */
    
    global $pdo;

    try {
        // Prepare the SQL statement with placeholders for limit and offset
        $stmt = $pdo->prepare("
            SELECT * 
            FROM custom_part_definitions 
            WHERE equipment_type = :type AND is_active = 1
        ");

        // Bind pagination parameters securely
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);

        // Execute the query and return the results
        $stmt->execute();

        // Return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function getCustomPartByName($name) {
    /*
        Function to fetch a custom part by its name.
        It prepares and executes a SELECT query that fetches a custom part by its name,
        and returns it as an associative array.

        Args:
        - $name: Name of the custom part to fetch.

        Returns:
        - Associative array of the custom part on success.
        - String containing error message.
    */

    global $pdo;

    try {
        // Prepare the SQL statement with placeholders
        $stmt = $pdo->prepare("
            SELECT * 
            FROM custom_part_definitions 
            WHERE part_name = :name
        ");

        // Bind parameters securely
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Return the results
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in getCustomPartByName: " . $e->getMessage());
        return "Database error occurred when fetching custom part by name: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
