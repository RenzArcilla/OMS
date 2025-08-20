<?php
/*
    Handles UPDATE operations for records in the database.
    Provides a function to update inspection date, shift, applicators, and machine.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateRecord($record_id, $date_inspected, $shift, $app1_id, $app2_id, $machine_id) {
    /*
        Updates an existing record in the database.
        Handles date, shift, applicators, and machine fields.
        
        Args:
        - $record_id: int, ID of the record to update
        - $date_inspected: string, date of inspection
        - $shift: string, work shift (FIRST, SECOND, NIGHT)
        - $app1_id: int, ID of first applicator
        - $app2_id: int, ID of second applicator
        - $machine_id: int, ID of the machine

        Returns:
        - true on success
        - string with error message on failure
    */

    global $pdo;

    switch (strtoupper($shift)) {
            case 'FIRST':
                $shift_formatted = '1st';
                break;
            case 'SECOND':
                $shift_formatted = '2nd';
                break;
            case 'NIGHT':
                $shift_formatted = 'NIGHT';
                break;
            default:
                return "Invalid shift value: " . htmlspecialchars($shift, ENT_QUOTES);
        }

    try {
        // Prepare SQL update query
        $stmt = $pdo->prepare("
            UPDATE records
            SET
                date_inspected = :date_inspected,
                shift = :shift,
                applicator1_id = :app1_id,
                applicator2_id = :app2_id,
                machine_id = :machine_id
            WHERE record_id = :record_id
        ");

        // Bind parameters
        $stmt->bindParam(':record_id', $record_id);
        $stmt->bindParam(':date_inspected', $date_inspected);
        $stmt->bindParam(':shift', $shift_formatted);
        $stmt->bindValue(':app1_id', $app1_id);
        $stmt->bindValue(':app2_id', $app2_id);
        $stmt->bindValue(':machine_id', $machine_id);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "Failed to update record. Please try again.";
        }
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateRecord: " . $e->getMessage());
        return "Database error in updateRecord: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}