<?php
/*
    This file contains functions that deletes records from the machine_output table.
*/


function disableMachineOutput($record_id): bool|string {
    /*
    Disable (soft delete) a machine output by record_id.

    Args:
    - $record_id: ID of the record to disable
    
    Returns:
    - true on success
    - string containing error message on failure
    */

    global $pdo;

    try {
        $stmt = $pdo->prepare("
            UPDATE machine_outputs
            SET is_active = 0
            WHERE record_id = :record_id
        ");
        $stmt->bindValue(':record_id', $record_id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Database Error in disableMachine(): " . $e->getMessage());
        return "Database Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function disableMachineOutputsByRecordIds(array $record_ids): bool|string {
    /*
    Function to disable (soft delete) multiple machine outputs in the database.

    Args:
    - $record_ids: array of record IDs to disable

    Returns:
    - true on success
    - string containing error message on failure
    */

    global $pdo;

    try {
        if (empty($record_ids)) {
            return true; // nothing to update
        }

        // Generate placeholders (?, ?, ?, ...)
        $placeholders = implode(',', array_fill(0, count($record_ids), '?'));

        // Build the SQL
        $sql = "UPDATE machine_outputs 
                SET is_active = 0 
                WHERE record_id IN ($placeholders)";

        $stmt = $pdo->prepare($sql);

        // Bind values
        foreach ($record_ids as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return true;

    } catch (PDOException $e) {
        error_log("Database Error in disableMachineOutputsByRecordIds(): " . $e->getMessage());
        return "Database Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}
