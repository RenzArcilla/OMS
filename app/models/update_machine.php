<?php
/*
    This file handles the UPDATE operation for machines.
    Updates an existing machine record in the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateMachine($machine_id, $control_no, $description, $model,  
                        $machine_maker, $serial_no, $invoice_no) {
    /*
        Update an existing machine in the database.

        Args:
        - $machine_id: int, ID of the machine
        - $control_no: string, control number of the machine
        - $description: string, description (AUTOMATIC/SEMI-AUTOMATIC)
        - $model: string, model of the machine
        - $machine_maker: string, maker of the machine
        - $serial_no: string|null, serial number of the machine
        - $invoice_no: string|null, invoice number of the machine

        Returns:
        - true on successful update
        - string containing error message on failure
    */

    global $pdo;
    
    // Set serial_no and invoice_no to null if empty or "NO RECORD"
    $serial_no = $serial_no === 'NO RECORD' ? null : $serial_no;
    $invoice_no = $invoice_no === 'NO RECORD' ? null : $invoice_no;

    try {
        // Prepare SQL update query
        $stmt = $pdo->prepare("
            UPDATE machines SET
                control_no = :control_no,
                description = :description,
                model = :model,
                maker = :machine_maker,
                serial_no = :serial_no,
                invoice_no = :invoice_no
            WHERE machine_id = :machine_id
        ");

        // Bind parameters
        $stmt->bindParam(':machine_id', $machine_id);
        $stmt->bindParam(':control_no', $control_no);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':machine_maker', $machine_maker);
        $stmt->bindParam(':serial_no', $serial_no, PDO::PARAM_NULL | PDO::PARAM_STR);
        $stmt->bindParam(':invoice_no', $invoice_no, PDO::PARAM_NULL | PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "Failed to update machine. Please try again.";
        }
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateMachine: " . $e->getMessage());
        return "Database error in updateMachine: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function restoreDisabledMachine($machine_id) {
    /*
        This function restores a disabled machine by updating its status in the database.

        Args:
        - $machine_id: int, ID of the machine

        Returns:
        - true on successful update
        - string containing error message on failure
    */

    global $pdo;
    
    try {
        // Prepare SQL update query
        $stmt = $pdo->prepare("
            UPDATE machines SET
                is_active = 1
            WHERE machine_id = :machine_id
        ");

        // Bind parameters
        $stmt->bindParam(':machine_id', $machine_id);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "Failed to update machine. Please try again.";
        }
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in restoreDisabledMachine: " . $e->getMessage());
        return "Database error when restoring machine: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}