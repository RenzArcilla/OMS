<?php
/*
    This file defines functions that query the machines table from the database.
    Used when fetching machine records for listing or validation, such as in
    pagination or infinite scroll.

    Functions included:
    - getMachines(PDO $pdo, int $limit = 10, int $offset = 0): Fetches a paginated list of active machines.
    - machineExists($control_no): Checks if a machine exists by control number.
    - getInactiveMachineByControlNo($control_no): Checks if a machine exists and is inactive.
    - getActiveMachineByControlNo($control_no): Checks if a machine exists and is active.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function getMachines(PDO $pdo, int $limit = 10, int $offset = 0): array {
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
    
    // Prepare the SQL statement with placeholders for limit and offset
    $stmt = $pdo->prepare("
        SELECT machine_id, control_no, description, model, maker, serial_no, invoice_no 
        FROM machines
        WHERE is_active = 1
        ORDER BY machine_id DESC 
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getDisabledMachines(int $limit = 10, int $offset = 0): array {
    /*
        Function to fetch a list of disabled machines from the database with pagination.
        It prepares and executes a SELECT query that fetches machines ordered by most recent,
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
        SELECT machine_id, control_no, model, maker, last_encoded 
        FROM machines
        WHERE is_active = 0
        ORDER BY machine_id DESC 
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function machineExists($control_no){
    /*
        Function to check if machine exists.

        Arg:
        - $control_no: unique identifier of the machine

        Returns:
        - Machine data if exists
        - False if machine does not exist
        - String containing error message
    */

    global $pdo;

    try {
        // Prepare SQL select query with first_name and last_name
        $stmt = $pdo->prepare("SELECT * FROM machines WHERE control_no = :control_no");

        // Bind parameters
        $stmt->bindParam(':control_no', $control_no);

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
        error_log("Database Error in getMachineByControlNo: " . $e->getMessage());
        return "A database error occurred while fetching machine data. Please try again later.";
    }
}

function getInactiveMachineByControlNo($control_no) {
    /*
        Retrieve inactive machine by control number.

        Args:
        - $control_no: string, control number of the machine

        Returns:
        - true if inactive machine exists
        - false if no inactive machine found
        - string containing error message on failure
    */

    global $pdo;

    try {
        // Prepare SQL select query with hp_no
        $stmt = $pdo->prepare("SELECT * FROM Machines WHERE control_no = :control_no AND is_active = 0");

        // Bind parameters
        $stmt->bindParam(':control_no', $control_no, PDO::PARAM_STR);

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
        error_log("Database Error in getInactiveMachineByControlNo: " . $e->getMessage());
        return "A database error occurred while checking machine existence. Please try again later.";
    }
}


function getActiveMachineByControlNo($control_no) {
    /*
        Retrieve active machine by control number.

        Args:
        - $control_no: string, control number of the machine

        Returns:
        - associative array of row with same control_no if active machine exists
        - false if no active machine found
        - string containing error message on failure
    */

    global $pdo;

    try {
        // Prepare SQL select query with control_no
        $stmt = $pdo->prepare("SELECT * FROM machines WHERE control_no = :control_no AND is_active = 1");

        // Bind parameters
        $stmt->bindParam(':control_no', $control_no, PDO::PARAM_STR);

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
        error_log("Database Error in getActiveMachineByControlNo: " . $e->getMessage());
        return "A database error occurred while checking machine existence. Please try again later.";
    }
}


function getFilteredMachines(int $limit = 20, int $offset = 0, ?string $search = null, string $description = 'ALL', int $is_active = 1): array {
    /*
        Function to fetch a list of machines with optional search and description filter.
        Supports pagination for efficient retrieval of machine records.

        Args:
        - $limit: Maximum number of rows to fetch (default is 20).
        - $offset: Number of rows to skip (default is 0), used for pagination.
        - $search: Optional search term to filter by control_no, model, maker,
                serial_no, or invoice_no.
        - $description: Optional filter for description values ("ALL", "SIDE", "END").
                        Defaults to "ALL" (no filter applied).

        Returns:
        - Array of machines (associative arrays) on success.
    */
    global $pdo;

    $sql = "
        SELECT machine_id, control_no, description, model, maker, serial_no, invoice_no
        FROM machines
        WHERE is_active = :is_active
    ";

    $params = [
        ':is_active' => $is_active
    ];

    // Add search condition if provided
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search); // escape LIKE wildcards
        $sql .= " AND (
            control_no LIKE :search OR
            model LIKE :search OR
            serial_no LIKE :search OR
            invoice_no LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    // Add description filter if not "ALL"
    if ($description !== 'ALL') {
        $sql .= " AND description = :description";
        $params[':description'] = $description;
    }

    $sql .= " ORDER BY last_encoded DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getMachinesForExport() {
    /*
        Function to fetch all active machines for export.

        Returns:
        - Array of machines (associative arrays) on success.
        - Empty array if no records found.
    */

    global $pdo;

    // Prepare the SQL statement
    $stmt = $pdo->prepare("
        SELECT 
            control_no, 
            description,
            model,
            maker, 
            serial_no, 
            invoice_no,
            last_encoded
        FROM 
            machines
        WHERE 
            is_active = 1
        ORDER BY
            machine_id DESC
    ");

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDisabledMachinesCount($search = null, $description = 'ALL') {
    /*
        Function to get the total count of disabled machines for pagination.
        Supports search and description filtering.

        Args:
        - $search: Optional search term to filter by control_no, model, maker, serial_no, or invoice_no
        - $description: Filter for description values ("ALL", "AUTOMATIC", "SEMI-AUTOMATIC")

        Returns:
        - Integer count of matching records
    */
    global $pdo;
    
    $query = "SELECT COUNT(*) FROM machines WHERE is_active = 0";
    $params = [];
    
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search);
        $query .= " AND (
            control_no LIKE :search OR
            model LIKE :search OR
            serial_no LIKE :search OR
            invoice_no LIKE :search
        )";
        $params[':search'] = "%$search%";
    }
    
    if ($description !== 'ALL') {
        $query .= " AND description = :description";
        $params[':description'] = $description;
    }
    
    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}


function fetchMachinesByControlNos(array $controlNos): array {
    /*
        Fetch machines by their control numbers
        Used when recording outputs for machines or applicators in batchLoadData.
    */
    global $pdo;

    // Return empty if no control numbers
    if (empty($controlNos)) return [];

    // Execute the query using placeholders
    $placeholders = implode(',', array_fill(0, count($controlNos), '?'));
    $sql = "SELECT * FROM machines WHERE control_no IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    foreach ($controlNos as $i => $val) {
        $stmt->bindValue($i + 1, $val, PDO::PARAM_STR);
    }
    $stmt->execute();
    $out = [];

    // Map by control_no
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $out[$row['control_no']] = $row;
    }
    return $out;
}


function countActiveMachines() {
    /*
        Count the total number of active machines in the database.

        Args:
        - $pdo (PDO): PDO database connection object.

        Returns:
        - int: Total number of active machines.
    */
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM machines WHERE is_active = 1");
    return (int) $stmt->fetchColumn();
}

function fetchAllMachines($is_active = 1) {
    /*
        Fetch all machines from the database.
        Supports filtering by active status.
        Used in the validation when adding a new machine to check for duplicates.

        Args:
        - $is_active (int): Filter by active status (1 for active, 0 for inactive). Default is 1.

        Returns:
        - array: Array of machines (associative arrays).
    */
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM machines WHERE is_active = :is_active");
    $stmt->bindValue(':is_active', $is_active, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}