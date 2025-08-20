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

