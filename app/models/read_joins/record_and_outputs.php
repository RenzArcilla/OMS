<?php
/*
    This file defines a function for fetching a paginated list of machines 
    and their outputs from the database. 

    Used in the machine listing with pagination features 
    (e.g., infinite scroll).
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 


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


function getFilteredRecords($limit, $offset, $search = null, $shift = 'ALL', $is_active = 1) {
    /*
        Function to fetch records with related applicator and machine outputs.
        Supports search, optional shift filter, active/disabled toggle, and pagination.
        Prevents SQL injection via parameter binding.

        Parameters:
            int $limit       - Maximum number of rows to fetch.
            int $offset      - Number of rows to skip.
            string $search   - Optional search keyword across multiple columns.
            string $shift    - Optional shift filter (default = 'ALL').
            int $is_active   - Filter by active status (1 = active, 0 = disabled).

        Returns:
            array - Associative array of filtered records.
    */

    global $pdo;

    $query = "
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
        WHERE r.is_active = :is_active
    ";

    $params = [':is_active' => $is_active];

    // Apply search filter
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search);
        $query .= " AND (
            r.record_id LIKE :search OR
            r.last_updated LIKE :search OR
            a1.hp_no LIKE :search OR
            a2.hp_no LIKE :search OR
            m.control_no LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    // Apply shift filter
    if ($shift !== 'ALL') {
        $query .= " AND r.shift = :shift";
        $params[':shift'] = $shift;
    }

    $query .= " ORDER BY r.record_id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);

    // Bind all parameters dynamically
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Bind pagination
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getFilteredRecordsForExport($date_range = 'today', $start_date = null, $end_date = null) {
    /*
        Model function to fetch filtered records.
        This function will be used for exporting records to Excel.

        Parameters:
            string $filters - The filters to apply when fetching records.
    */
    
    global $pdo;

    $params = [];
    switch ($date_range) {
        case 'today':
            $filters = "DATE(r.date_inspected) = CURDATE()";
            break;
        case 'week':
            $filters = "YEARWEEK(r.date_inspected, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'month':
            $filters = "MONTH(r.date_inspected) = MONTH(CURDATE()) 
                        AND YEAR(r.date_inspected) = YEAR(CURDATE())";
            break;
        case 'quarter':
            $filters = "QUARTER(r.date_inspected) = QUARTER(CURDATE()) 
                        AND YEAR(r.date_inspected) = YEAR(CURDATE())";
            break;
        case 'custom':
            $filters = "DATE(r.date_inspected) BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $start_date;
            $params[':end_date']   = $end_date;
            break;
        default:
            $filters = "1=1"; // all
    }

    $sql = "
        SELECT 
            r.record_id AS record_id,
            r.shift AS shift,
            r.date_inspected AS date_inspected,
            r.date_encoded AS date_encoded,
            r.last_updated AS last_updated,

            a1.hp_no AS hp1_no,
            ao1.total_output AS app1_output,

            a2.hp_no AS hp2_no,
            ao2.total_output AS app2_output,

            m.control_no AS control_no,
            mo.total_machine_output AS machine_output

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
        AND $filters
        ORDER BY r.record_id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRecordsCount($search = null, $shift = 'ALL') {
    /*
        Function to count active records from the database with optional search and filters.
        Used for pagination calculations.

        Parameters:
            string $search    - Optional search keyword for multiple fields.
            string $shift     - Optional filter for shift (default = 'ALL').

        Returns:
            int - Count of active records matching the search and filter criteria.
    */

    global $pdo;

    $query = "SELECT COUNT(*) FROM records r
        LEFT JOIN applicator_outputs ao1 
            ON r.record_id = ao1.record_id 
            AND r.applicator1_id = ao1.applicator_id

        LEFT JOIN applicator_outputs ao2 
            ON r.record_id = ao2.record_id 
            AND r.applicator2_id = ao2.applicator_id

        LEFT JOIN machine_outputs mo 
            ON r.record_id = mo.record_id 
            AND r.machine_id = mo.machine_id

        WHERE r.is_active = 1";

    $params = [];

    // Add shift filter
    if ($shift !== 'ALL') {
        $query .= " AND r.shift = :shift";
        $params[':shift'] = $shift;
    }

    // Add search filter
    if ($search !== null) {
        $query .= " AND (
            UPPER(r.record_id) LIKE :search OR
            UPPER(r.date_inspected) LIKE :search OR
            UPPER(r.shift) LIKE :search OR
            UPPER(r.hp1_no) LIKE :search OR
            UPPER(r.hp2_no) LIKE :search OR
            UPPER(r.control_no) LIKE :search OR
            UPPER(ao1.output) LIKE :search OR
            UPPER(ao2.output) LIKE :search OR
            UPPER(mo.output) LIKE :search
        )";
        $params[':search'] = '%' . $search . '%';
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
}

function getDisabledRecordsCount($search = null, $shift = 'ALL') {
    /*
        Function to count disabled records from the database with optional search and filters.
        Used for pagination calculations.

        Parameters:
            string $search    - Optional search keyword for multiple fields.
            string $shift     - Optional filter for shift (default = 'ALL').

        Returns:
            int - Count of disabled records matching the search and filter criteria.
    */

    global $pdo;

    $query = "SELECT COUNT(*) FROM records r
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
        WHERE r.is_active = 0";
    
    $params = [];

    // Add search condition if provided
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search); // escape LIKE wildcards
        $query .= " AND (
            r.record_id LIKE :search OR
            r.last_updated LIKE :search OR
            a1.hp_no LIKE :search OR
            a2.hp_no LIKE :search OR
            m.control_no LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    // Apply shift filter if provided
    if ($shift !== 'ALL') {
        $query .= " AND r.shift = :shift";
        $params[':shift'] = $shift;
    }

    $stmt = $pdo->prepare($query);

    // Bind dynamic parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    return (int)$stmt->fetchColumn();
}