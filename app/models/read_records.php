<?php
/*
    This file contains READ operation the records table.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';


function getRecordsLaterThanTimestamp($timestamp) {
    /*
    Fetches all records encoded later than a timestamp in the database.

    Args:
    - $timestamp: date_time

    Returns:
    - array of records (possibly empty) on success
    - string containing error message on failure
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