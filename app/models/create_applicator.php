<?php
/*
    This file defines a function to CREATE applicator entries in the database.
    Handles inserting applicator details into the applicators table.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function createApplicator($control_no, $terminal_no, $description, 
                        $wire_type, $terminal_maker, $applicator_maker, 
                        $serial_no, $invoice_no) {
    /*
        Function to create a new applicator in the database.
        
        Args:
        - $control_no: Control number of the applicator.
        - $terminal_no: Terminal number of the applicator.
        - $description: Description of the applicator (SIDE/END).
        - $wire_type: Type of wire used in the applicator (BIG/SMALL).
        - $terminal_maker: Maker of the terminal.
        - $applicator_maker: Maker of the applicator.
        - $serial_no: Serial number of the applicator.
        - $invoice_no: Invoice number associated with the applicator.
        
        Returns:
        - true on successful operation.
        - string containing error message.
    */

    global $pdo;
    
    // Set serial_no and invoice_no to null if empty or "NO RECORD"
    $serial_no = $serial_no === 'NO RECORD' ? null : $serial_no;
    $invoice_no = $invoice_no === 'NO RECORD' ? null : $invoice_no;

    try {
        // Prepare SQL insert query
        $stmt = $pdo->prepare("
            INSERT INTO applicators (hp_no, terminal_no, description, wire, 
                                    terminal_maker, applicator_maker, serial_no, invoice_no, last_encoded)
            VALUES (:control_no, :terminal_no, :description, :wire_type, 
                    :terminal_maker, :applicator_maker, :serial_no, :invoice_no, NOW())
        ");

        // Bind parameters
        $stmt->bindParam(':control_no', $control_no);
        $stmt->bindParam(':terminal_no', $terminal_no);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':wire_type', $wire_type);
        $stmt->bindParam(':terminal_maker', $terminal_maker);
        $stmt->bindParam(':applicator_maker', $applicator_maker);
        $stmt->bindParam(':serial_no', $serial_no, PDO::PARAM_NULL | PDO::PARAM_STR);
        $stmt->bindParam(':invoice_no', $invoice_no, PDO::PARAM_NULL | PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "Failed to add applicator. Please try again.";
        }
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}