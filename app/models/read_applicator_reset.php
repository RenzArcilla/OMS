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
