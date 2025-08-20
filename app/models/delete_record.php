<?php
/*
    This file contains functions for disabling (soft DELETE-ing) rows 
    in the records table of the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function disableRecord($record_id): bool|string {
    /*
        Function to disable a record in the database.

        Args:
        - $record_id: ID of the record to disable.

        Returns:
        - True on success, string containing error message on failure.
    */
    global $pdo;

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("UPDATE records SET is_active = 0 WHERE record_id = :record_id");
        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Log error
        error_log("Database Error on disableRecord: " . $e->getMessage());
        return "Database Error on disableRecord: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function disableRecordEncodedLaterThan($timestamp): bool|string {
    /*
        Function to disable (soft delete) all records encoded later than a timestamp in the database.

        Args:
        - $timestamp: date_time

        Returns:
        - true on success
        - string containing error message on failure
    */
    global $pdo;

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("
            UPDATE records 
            SET is_active = 0
            WHERE date_encoded > :timestamp
        ");

        $stmt->bindValue(':timestamp', $timestamp);

        // Execute the statement
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        // Log error
        error_log("Database Error on disableRecordEncodedLaterThan: " . $e->getMessage());
        return "Database Error on disableRecordEncodedLaterThan: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}