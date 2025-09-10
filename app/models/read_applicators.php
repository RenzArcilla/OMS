<?php
/*
    This file defines functions that queries (READ) the applicators table from the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function getApplicators(PDO $pdo, int $limit = 10, int $offset = 0): array {
    /*
        Retrieve applicator records from the database with pagination.
        Used in the applicator listing with pagination, such as in infinite scroll.

        This function fetches applicator rows ordered by latest ID, limited by
        the given amount and offset. Used for infinite scroll in the frontend.

        Args:
        - $pdo (PDO): PDO database connection object.
        - $limit (int): Number of rows to fetch.
        - $offset (int): Number of rows to skip.

        Returns:
        - array: Associative array of applicator records.
    */


    $stmt = $pdo->prepare("SELECT applicator_id, hp_no, terminal_no, description, wire, terminal_maker, applicator_maker, serial_no, invoice_no
                        FROM applicators
                        WHERE is_active = 1
                        ORDER BY applicator_id DESC
                        LIMIT :limit OFFSET :offset");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getDisabledApplicators(int $limit = 10, int $offset = 0): array {
    /*
        Function to fetch a list of disabled applicators from the database with pagination.
        It prepares and executes a SELECT query that fetches applicators ordered by most recent,
        and returns them as an associative array.

        Args:
        - $limit: Maximum number of rows to fetch (default is 10).
        - $offset: Number of rows to skip (default is 0), used for pagination.

        Returns:
        - Array of applicators (associative arrays) on success.
    */

    global $pdo;

    // Prepare the SQL statement with placeholders for limit and offset
    $stmt = $pdo->prepare("
        SELECT applicator_id, hp_no, description, terminal_maker, applicator_maker, last_encoded
        FROM applicators
        WHERE is_active = 0
        ORDER BY applicator_id DESC 
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function applicatorExists($hp_no){
    /*
        Function to check if applicator exists.

        Arg:
        - $hp_no: unique identifier of the applicator

        Returns:
        - Applicator data if exists
        - False if applicator does not exist
        - String containing error message
    */

    global $pdo;


    try {
        // Prepare SQL select query with first_name and last_name
        $stmt = $pdo->prepare("SELECT * FROM applicators WHERE hp_no = :hp_no");

        // Bind parameters
        $stmt->bindParam(':hp_no', $hp_no);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if data is empty (no record found)
        if (!$data) {
            return false;
        }

        return $data;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in applicatorExists: " . $e->getMessage());
        return "A database error occurred while checking applicator existence. Please try again later.";
    }
}


function getInactiveApplicatorByHpNo($hp_no) {
    /*
        Function to check if an inactive applicator exists by HP number.

        Args:
        - $hp_no: HP number of the applicator.

        Returns:
        - True if an inactive applicator exists.
        - False if no inactive applicator exists.
        - String containing error message on failure.
    */

    global $pdo;

    try {
        // Prepare SQL select query with hp_no
        $stmt = $pdo->prepare("SELECT * FROM applicators WHERE hp_no = :hp_no AND is_active = 0");

        // Bind parameters
        $stmt->bindParam(':hp_no', $hp_no, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if data is empty (no record found)
        if (!$data) {
            return false;
        }

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in getInactiveApplicatorByHpNo: " . $e->getMessage());
        return "A database error occurred while checking applicator existence. Please try again later.";
    }
}


function getActiveApplicatorByHpNo($hp_no) {
    /*
        Function to check if an active applicator exists by HP number.

        Args:
        - $hp_no: HP number of the applicator.

        Returns:
        - associative array of row with same hp_no if active machine exists
        - False if no active applicator exists.
        - String containing error message on failure.
    */

    global $pdo;

    try {
        // Prepare SQL select query with hp_no
        $stmt = $pdo->prepare("SELECT * FROM applicators WHERE hp_no = :hp_no AND is_active = 1");

        // Bind parameters
        $stmt->bindParam(':hp_no', $hp_no, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if data is empty (no record found)
        if (!$data) {
            return false;
        }

        return $data;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in getActiveApplicatorByHpNo: " . $e->getMessage());
        return "A database error occurred while checking applicator existence. Please try again later.";
    }
}


function getFilteredApplicators($limit, $offset, $search = null, $description = 'ALL', $type = 'ALL', $is_active = 1) {
    /*
        Function to fetch applicators from the database with optional search and filters.
        Supports pagination via limit and offset, and prevents SQL injection.

        Parameters:
            int $limit        - Maximum number of rows to fetch.
            int $offset       - Number of rows to skip for pagination.
            string $search    - Optional search keyword for multiple fields.
            string $description - Optional filter for applicator description (default = 'ALL').
            string $type      - Optional filter for wire type (default = 'ALL').
            int $is_active    - Optional filter for active status (default = 1).

        Returns:
            array - Associative array of applicators matching the search and filter criteria.
    */

    global $pdo;

    $query = "SELECT * FROM applicators WHERE is_active = :is_active";
    $params = [
        ':is_active' => $is_active
    ];

    // Add search condition if provided
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search); // escape LIKE wildcards
        $query .= " AND (
            hp_no LIKE :search OR 
            terminal_no LIKE :search OR 
            serial_no LIKE :search OR 
            invoice_no LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    // Apply description filter if provided
    if ($description !== 'ALL') {
        $query .= " AND description = :description";
        $params[':description'] = $description;
    }

    // Apply wire type filter if provided
    if ($type !== 'ALL') {
        $query .= " AND wire = :type";
        $params[':type'] = $type;
    }

    $query .= " ORDER BY applicator_id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);

    // Bind dynamic parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Bind pagination
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getApplicatorsForExport() {
    /*
        Function to fetch all active applicators for export.

        Returns:
        - Array of applicators (associative arrays) on success.
        - Empty array if no records found.
    */

    global $pdo;

    // Prepare the SQL statement
    $stmt = $pdo->prepare("
        SELECT 
            hp_no, 
            terminal_no, 
            description, 
            wire, 
            terminal_maker, 
            applicator_maker, 
            serial_no, 
            invoice_no,
            last_encoded
        FROM 
            applicators
        WHERE 
            is_active = 1
        ORDER BY
            applicator_id DESC
    ");

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getApplicatorsCount($search = null, $description = 'ALL', $type = 'ALL') {
    /*
        Function to count active applicators from the database with optional search and filters.
        Used for pagination calculations.

        Parameters:
            string $search        - Optional search keyword for multiple fields.
            string $description   - Optional filter for applicator description (default = 'ALL').
            string $type         - Optional filter for wire type (default = 'ALL').

        Returns:
            int - Total count of active applicators matching the criteria.
    */

    global $pdo;
    $query = "SELECT COUNT(*) FROM applicators WHERE is_active = 1";
    $params = [];

    // Add search condition if provided
    if ($search) {
        $query .= " AND (hp_no LIKE :search OR terminal_no LIKE :search OR description LIKE :search OR wire LIKE :search OR terminal_maker LIKE :search OR applicator_maker LIKE :search OR serial_no LIKE :search OR invoice_no LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // Add description filter if not 'ALL'
    if ($description !== 'ALL') {
        $query .= " AND description = :description";
        $params[':description'] = $description;
    }

    // Add type filter if not 'ALL'
    if ($type !== 'ALL') {
        $query .= " AND wire = :type";
        $params[':type'] = $type;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function getDisabledApplicatorsCount($search = null, $description = 'ALL', $type = 'ALL') {
    /*
        Function to count disabled applicators from the database with optional search and filters.
        Used for pagination calculations.

        Parameters:
            string $search    - Optional search keyword for multiple fields.
            string $description - Optional filter for applicator description (default = 'ALL').
            string $type      - Optional filter for wire type (default = 'ALL').

        Returns:
            int - Count of disabled applicators matching the search and filter criteria.
    */

    global $pdo;

    $query = "SELECT COUNT(*) FROM applicators WHERE is_active = 0";
    $params = [];

    // Add search condition if provided
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search); // escape LIKE wildcards
        $query .= " AND (
            hp_no LIKE :search OR 
            terminal_no LIKE :search OR 
            serial_no LIKE :search OR 
            invoice_no LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    // Apply description filter if provided
    if ($description !== 'ALL') {
        $query .= " AND description = :description";
        $params[':description'] = $description;
    }

    // Apply wire type filter if provided
    if ($type !== 'ALL') {
        $query .= " AND wire = :type";
        $params[':type'] = $type;
    }

    $stmt = $pdo->prepare($query);

    // Bind dynamic parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

function fetchApplicatorsByHpNos(array $hpNos): array {
    /*
        Fetch applicators by their HP numbers
        Used when recording outputs for machines or applicators in batchLoadData.
    */

    global $pdo;

    // Return empty if no HP numbers
    if (empty($hpNos)) return [];

    // Execute the query using placeholders
    $placeholders = implode(',', array_fill(0, count($hpNos), '?'));
    $sql = "SELECT * FROM applicators WHERE hp_no IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    foreach ($hpNos as $i => $val) {
        $stmt->bindValue($i + 1, $val, PDO::PARAM_STR);
    }
    $stmt->execute();
    $out = [];

    // Map by hp_no
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $out[$row['hp_no']] = $row;
    }
    return $out;
}

function countActiveApplicators() {
    /*
        Count the total number of active applicators in the database.

        Args:
        - $pdo (PDO): PDO database connection object.

        Returns:
        - int: Total number of active applicators.
    */
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM applicators WHERE is_active = 1");
    return (int) $stmt->fetchColumn();
}


function fetchAllApplicators($is_active = 1) {
    /*
        Fetch all applicators from the database.
        Supports filtering by active status.
        Used in the validation when adding a new applicator to check for duplicates.

        Args:
        - $is_active (int): Filter by active status (1 for active, 0 for inactive). Default is 1.

        Returns:
        - array: Array of applicators (associative arrays).
    */
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM applicators WHERE is_active = :is_active");
    $stmt->bindValue(':is_active', $is_active, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}