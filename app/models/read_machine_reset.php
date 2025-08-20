<?php
/*
    This file defines functions that query (READ) the machine_reset table from the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function getMachineReset($machine_id, $part_name) {
    /*
    Retrieve machine_reset records for a specific machine and part.

    Args:
    - $machine_id : int, 
    - $part_name: string, name of machine part

    Returns:
    - array: Associative array of machine_reset records (empty array if none)
    */

    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM machine_reset
        WHERE machine_id = :machine_id
            AND part_reset = :part_reset
        ORDER BY reset_time DESC
    ");

    $stmt->bindValue(':machine_id', $machine_id, PDO::PARAM_INT);
    $stmt->bindValue(':part_reset', $part_name, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




function getMachineResetOnTimeStamp($machine_id, $part_name, $reset_time) {
    /*
    Retrieve a machine_reset records for a specific machine and part.

    Args:
    - $machine_id : int, pertains to an machine
    - $part_name: string, name of machine part
    - $reset_time: time of reset

    Returns:
    - array: associative array of machine_reset records (empty array if none)
    - string: contains error message
    */

    global $pdo;

    try {
        // Prepare SQL select query with first_name and last_name
        $stmt = $pdo->prepare("
            SELECT * 
            FROM machine_reset 
            WHERE machine_id = :machine_id
                AND part_reset = :part_name
                AND reset_time = :reset_time");

        // Bind parameters
        $stmt->bindParam(':machine_id', $machine_id);
        $stmt->bindParam(':part_name', $part_name);
        $stmt->bindParam(':reset_time', $reset_time);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in getMachineResetOnTimeStamp: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}