<?php
/*
    This file defines a function that queries a list of cumulative applicator outputs from the database.
    Used in the applicator dashboard table.
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 

function getRecordsAndOutputs(int $limit = 10, int $offset = 0, array $part_names_array): array {
    /*
    Function to fetch a list of cumulative applicator outputs from the database with pagination.
    It prepares and executes a SELECT query that fetches applicators ordered by highest output,
    and returns them as an associative array.

    Args:
    - $limit: Maximum number of rows to fetch (default is 10).
    - $offset: Number of rows to skip (default is 0), used for pagination.

    Returns:
    - Array of machines (associative arrays) on success.
    */

    global $pdo;

    // Prepare the SQL statement with placeholders for limit and offset
    $stmt = $pdo->prepare("
        SELECT *
        FROM monitor_applicator
        LEFT JOIN applicators
            USING (applicator_id)
        ORDER BY total_output DESC
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $part_names_array = [];

    foreach ($records as &$row) {
        if (!empty($row['custom_parts_output'])) {
            $decoded = json_decode($row['custom_parts_output'], true);
            $row['custom_parts_output'] = is_array($decoded) ? $decoded : [];
        } else {
            $row['custom_parts_output'] = [];
        }
    }

    if (!empty($records[0]['custom_parts_output'])) {
        $part_names_array = array_keys($records[0]['custom_parts_output']);
    }

    return $records;
}
