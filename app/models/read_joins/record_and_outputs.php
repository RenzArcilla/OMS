<?php
/*
    This file defines a function for fetching a paginated list of machines 
    and their outputs from the database. 

    Used in the machine listing with pagination features 
    (e.g., infinite scroll).
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 

function getRecordsAndOutputs(int $limit = 20, int $offset = 0): array {
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
            r.last_updated AS last_updated,

            ao1.total_output AS app1_output,
            ao2.total_output AS app2_output,

            mo.total_machine_output AS machine_output,

            a1.hp_no AS hp1_no,
            a2.hp_no AS hp2_no,

            m.control_no AS control_no

        FROM records r
        LEFT JOIN applicator_outputs ao1 
            ON r.record_id = ao1.record_id 
            AND r.applicator1_id = ao1.applicator_id

        LEFT JOIN applicator_outputs ao2 
            ON r.record_id = ao2.record_id 
            AND r.applicator2_id = ao2.applicator_id


        LEFT JOIN machine_outputs mo ON r.record_id = mo.record_id

        LEFT JOIN applicators a1 ON r.applicator1_id = a1.applicator_id
        LEFT JOIN applicators a2 ON r.applicator2_id = a2.applicator_id

        LEFT JOIN machines m ON r.machine_id = m.machine_id

        WHERE r.is_active = 1
        ORDER BY r.last_updated DESC
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getDisabledRecordsAndOutputs($limit = 20, $offset = 0, $search = null): array {
    /*
        Fetch disabled records with related outputs and machine details.
        Supports optional search and pagination, with protection against SQL injection.

        Args:
        - $limit: Max rows to fetch (default 20).
        - $offset: Rows to skip (default 0).
        - $search: Optional search string.

        Returns:
        - Array of disabled records (associative arrays).
    */

    global $pdo;

    // Escape LIKE wildcards if search is used
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search);
    }

    $sql = "
        SELECT 
            r.record_id AS record_id,
            r.shift AS shift,
            r.date_inspected AS date_inspected,
            r.date_encoded AS date_encoded,
            r.last_updated AS last_updated,

            ao1.total_output AS app1_output,
            ao2.total_output AS app2_output,

            mo.total_machine_output AS machine_output,

            a1.hp_no AS hp1_no,
            a2.hp_no AS hp2_no,

            m.control_no AS control_no

        FROM records r
        LEFT JOIN applicator_outputs ao1 
            ON r.record_id = ao1.record_id 
            AND r.applicator1_id = ao1.applicator_id

        LEFT JOIN applicator_outputs ao2 
            ON r.record_id = ao2.record_id 
            AND r.applicator2_id = ao2.applicator_id

        LEFT JOIN machine_outputs mo ON r.record_id = mo.record_id
        LEFT JOIN applicators a1 ON r.applicator1_id = a1.applicator_id
        LEFT JOIN applicators a2 ON r.applicator2_id = a2.applicator_id
        LEFT JOIN machines m ON r.machine_id = m.machine_id

        WHERE r.is_active = 0
    ";

    // Add search condition if provided
    if (!empty($search)) {
        $sql .= " AND (
            r.record_id LIKE :search OR
            a1.hp_no LIKE :search OR
            a2.hp_no LIKE :search OR
            m.control_no LIKE :search OR
            r.last_updated LIKE :search
        )";
    }

    $sql .= " ORDER BY r.last_updated DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getFilteredRecords($limit, $offset, $search = null, $shift = 'ALL') {
    /*
        Function to fetch records and outputs from the database with optional search and shift filter.
        Supports pagination via limit and offset, and prevents SQL injection.

        Parameters:
            int $limit        - Maximum number of rows to fetch.
            int $offset       - Number of rows to skip for pagination.
            string $search    - Optional search keyword across multiple columns.
            string $shift     - Optional shift filter (default = 'ALL').

        Returns:
            array - Associative array of records matching the search and filter criteria.
    */

    global $pdo;

    $sql = "SELECT * FROM record_and_outputs WHERE 1=1";
    $params = [];

    // Apply search filter if provided
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search); // escape LIKE wildcards
        $sql .= " AND (
            record_id LIKE :search OR
            last_updated LIKE :search OR
            hp1_no LIKE :search OR
            hp2_no LIKE :search OR
            control_no LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    // Apply shift filter if provided
    if ($shift !== 'ALL') {
        $sql .= " AND shift = :shift";
        $params[':shift'] = $shift;
    }

    $sql .= " ORDER BY record_id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    // Bind dynamic parameters securely
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
