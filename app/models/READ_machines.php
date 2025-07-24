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
        return $data;

    } catch (PDOException $e) {
        // Log error and return false on failure
        error_log("Database Error: " . $e->getMessage());
        return "<script>
            alert('Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "');
            window.location.href = '../templates/record_output.php';</script>";
    }
}