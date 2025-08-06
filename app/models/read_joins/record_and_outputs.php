<?php
/*
    This file defines a function that queries a list of machines from the database.
    Used in the machine listing with pagination, such as in infinite scroll.
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 

function getRecordsAndOutputs(int $limit = 10, int $offset = 0): array {
    /*
    Function to fetch a list of machines from the database with pagination.
    It prepares and executes a SELECT query that fetches machines ordered by most recent,
    and returns them as an associative array.

    Args:
    - $pdo: PDO database connection object.
    - $limit: Maximum number of rows to fetch (default is 10).
    - $offset: Number of rows to skip (default is 0), used for pagination.

    Returns:
    - Array of machines (associative arrays) on success.
    */

    global $pdo;

    // Prepare the SQL statement with placeholders for limit and offset
    $stmt = $pdo->prepare("
        SELECT 
            r.record_id AS record_id,
            r.shift AS shift,
            r.date_inspected AS date_inspected,
            r.date_encoded AS date_encoded,

            ao1.total_output AS app1_output,
            ao2.total_output AS app2_output,

            mo.total_machine_output AS machine_output,

            a1.hp_no AS hp1_no,
            a2.hp_no AS hp2_no,

            m.control_no AS control_no

        FROM records r
        LEFT JOIN applicator_outputs ao1 ON r.applicator1_id = ao1.applicator_output_id
        LEFT JOIN applicator_outputs ao2 ON r.applicator2_id = ao2.applicator_output_id

        LEFT JOIN machine_outputs mo ON r.machine_id = mo.machine_output_id

        LEFT JOIN applicators a1 ON r.applicator1_id = a1.applicator_id
        LEFT JOIN applicators a2 ON r.applicator2_id = a2.applicator_id

        LEFT JOIN machines m ON r.machine_id = m.machine_id

        WHERE r.is_active = 1
        ORDER BY r.record_id DESC
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

