<?php
/*
    This file defines a function that queries a list of machines from the database.
    Used in the machine listing with pagination, such as in infinite scroll.
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


function machineExists($control_no){
    /*
    Function to check if machine exists.

    Arg:
    - $control_no: unique identifier of the machine

    Returns:
    - Machine data if exists
    - False if machine does not exist
    - String containing error message and redirect using JS <alert>.
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
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

function getInactiveMachineByControlNo($control_no) {
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
        return "Database error occurred in getInactiveMachineByControlNo: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function getActiveMachineByControlNo($control_no) {
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

        return true;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in getActiveMachineByControlNo: " . $e->getMessage());
        return "Database error occurred in getActiveMachineByControlNo: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}