<?php
/*
    This file defines a function that queries the records table (READ).
    Fetches all records encoded later than a given timestamp.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';


function getRecordsLaterThanTimestamp($timestamp) {
    /*
        Retrieve records from the database that were encoded after a specific timestamp.

        Args:
        - $timestamp: string, date-time to compare

        Returns:
        - array: associative array of records (empty array if none)
        - string: error message on failure
    */
    
    global $pdo;

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("
            SELECT * FROM records 
            WHERE date_encoded > :timestamp
        ");

        $stmt->bindParam(':timestamp', $timestamp);

        // Execute the statement
        $stmt->execute(); 

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Log error
        error_log("Database Error on getRecordsLaterThanTimestamp: " . $e->getMessage());
        return "Database Error on getRecordsLaterThanTimestamp: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}