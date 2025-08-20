<?php
/*
    This file contains functions for DELETE-ing and disabling applicator outputs in the database.
    It supports hard deletion, soft deletion, and bulk disabling of records.

    Functions:
    - deleteApplicatorOutputs(): Permanently deletes an applicator output.
    - disableApplicatorOutput(): Soft deletes a single applicator output by record_id.
    - disableApplicatorOutputsByRecordIds(): Soft deletes multiple applicator outputs at once.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 


function deleteApplicatorOutputs($applicator_id, $record_id) {
    /*
        Function to delete an applicator output in the database.

        Args:
        - $applicator_id: ID of the applicator to delete.
        - $record_id: record ID of the applicator to delete
        
        Returns:
        - True on success, string containing error message on failure.
    */

    global $pdo;

    try {
        // Prepare the SQL DELETE statement
        $stmt = $pdo->prepare("
            DELETE FROM applicator_outputs
            WHERE applicator_id = :applicator_id
                AND record_id = :record_id
        ");
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':record_id', $record_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Log error and return a sanitized error message
        error_log("Database Error in deleteApplicatorOutputs(): " . $e->getMessage());
        return "Database Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function disableApplicatorOutput($record_id): bool|string {
    /*
        Disable (soft delete) applicator outputs by record_id.

        Args:
        - $record_id: ID of the record to disable
        
        Returns:
        - true on success
        - string containing error message on failure
    */

    global $pdo;

    try {
        $stmt = $pdo->prepare("
            UPDATE applicator_outputs
            SET is_active = 0
            WHERE record_id = :record_id
        ");
        $stmt->bindValue(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Database Error in disableApplicatorOutput(): " . $e->getMessage());
        return "Database Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function disableApplicatorOutputsByRecordIds(array $record_ids): bool|string {
    /*
        Disable applicator_outputs by multiple record_ids in one query.

        Args:
        - $record_ids: array of record IDs

        Returns:
        - true on success
        - error message string on failure
    */
    global $pdo;

    try {
        if (empty($record_ids)) {
            return true; // nothing to disable
        }

        // Generate placeholders like ?, ?, ? based on number of IDs
        $placeholders = implode(',', array_fill(0, count($record_ids), '?'));

        $sql = "UPDATE applicator_outputs SET is_active = 0 WHERE record_id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);

        // Bind all IDs
        foreach ($record_ids as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        error_log("Database Error on disableApplicatorOutputsByRecordIds: " . $e->getMessage());
        return "Database Error on disableApplicatorOutputsByRecordIds: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
