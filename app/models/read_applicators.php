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


    $stmt = $pdo->prepare("SELECT hp_no, terminal_no, description, wire, terminal_maker, applicator_maker, serial_no, invoice_no
                        FROM applicators
                        ORDER BY applicator_id DESC
                        LIMIT :limit OFFSET :offset");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
    - String containing error message and redirect using JS <alert>.
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
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}