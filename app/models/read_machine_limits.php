<?php
/*
    This file contains functions for querying machine limits. 
    
    Used primarily in the Machine Dashboard for displaying and analyzing 
    machine usage and part output data.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function getMachinePartLimits() {
    /*
        Fetch all machine part limits from the database.
        Returns an associative array of machine part limits.

        Used in the machine dashboard to determine part limits for each machine.
    */

    global $pdo;

    $stmt = $pdo->prepare("SELECT 
        machine_id,
        machine_part,
        part_limit
        FROM machine_part_limits");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}