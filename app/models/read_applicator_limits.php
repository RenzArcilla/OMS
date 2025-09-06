<?php
/*
    This file contains functions for querying applicator limits. 
    
    Used primarily in the Applicator Dashboard for displaying and analyzing 
    applicator usage and part output data.
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php';

function getApplicatorPartLimits() {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT 
        applicator_id,
        applicator_part,
        part_limit
        FROM applicator_part_limits");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}