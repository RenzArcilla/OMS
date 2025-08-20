<?php
/*
    This file defines functions that query (READ) the applicator_reset table from the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function getApplicatorReset($applicator_id, $part_name) {
    /*
    Retrieve applicator_reset records for a specific applicator and part.

    Args:
    - $applicator_id : int, 
    - $part_name: string, name of applicator part

    Returns:
    - array: Associative array of applicator_reset records (empty array if none)
    */

    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM applicator_reset
        WHERE applicator_id = :applicator_id
            AND part_reset = :part_reset
        ORDER BY reset_time DESC
    ");

    $stmt->bindValue(':applicator_id', $applicator_id, PDO::PARAM_INT);
    $stmt->bindValue(':part_reset', $part_name, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getApplicatorResetOnTimeStamp($applicator_id, $part_name, $reset_time) {
    /*
    Retrieve an applicator_reset records for a specific applicator and part.

    Args:
    - $applicator_id : int, pertains to an applicator
    - $part_name: string, name of applicator part
    - $reset_time: time of reset

    Returns:
    - array: associative array of applicator_reset records (empty array if none)
    - string: contains error message
    */

    global $pdo;

    try {
        // Prepare SQL select query with first_name and last_name
        $stmt = $pdo->prepare("
            SELECT * 
            FROM applicator_reset 
            WHERE applicator_id = :applicator_id
                AND part_reset = :part_name
                AND reset_time = :reset_time");

        // Bind parameters
        $stmt->bindParam(':applicator_id', $applicator_id);
        $stmt->bindParam(':part_name', $part_name);
        $stmt->bindParam(':reset_time', $reset_time);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}