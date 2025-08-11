<?php
/*
    This file defines a function that queries a list of machines from the database.
    Used in the machine listing with pagination, such as in infinite scroll.
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 

function getRecordsAndOutputs(int $limit = 20, int $offset = 0): array {
    /*
    Function to fetch a list of machines from the database with pagination.
    It prepares and executes a SELECT query that fetches machines ordered by most recent,
    and returns them as an associative array.

    Args:
    - $limit: Maximum number of rows to fetch (default is 20).
    - $offset: Number of rows to skip (default is 0), used for pagination.

    Returns:
    - Array of machine outputs and machine part outputs (associative arrays) on success.
    */

    global $pdo;

    // Prepare the SQL statement with placeholders for limit and offset
    $stmt = $pdo->prepare("
        SELECT 
            m.control_no AS control_no,
            mm.total_machine_output AS machine_output,
            mm.cut_blade_output AS cut_blade_output,
            mm.strip_blade_a_output AS strip_blade_a_output,
            mm.strip_blade_b_output AS strip_blade_b_output,
            mm.custom_parts_output AS custom_parts_output,
            mm.last_updated AS last_updated

        FROM machines m
        LEFT JOIN monitor_machine mm
            ON m.machine_id = mm.machine_id 

        ORDER BY mm.last_updated DESC
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Decode JSON for each row
    foreach ($results as &$row) {
        if (!empty($row['custom_parts_output'])) {
            $row['custom_parts_output'] = json_decode($row['custom_parts_output'], true) ?? [];
        } else {
            $row['custom_parts_output'] = [];
        }
    }

    return $results;
}
