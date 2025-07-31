<?php
/*
    This script handles the UPDATE operation for applicators in the database.
    It includes a function to update applicator data in the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function updateApplicator($applicator_id, $control_no, $terminal_no, $description, 
                        $wire_type, $terminal_maker, $applicator_maker, 
                        $serial_no, $invoice_no) {
    /*
    Function to update an existing applicator in the database.

    Args:
    - $applicator_id: ID of the applicator to update.
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
    - string containing error message and redirect using JS <alert>.
    */

    global $pdo;
    
    // Set serial_no and invoice_no to null if empty or "NO RECORD"
    $serial_no = $serial_no === 'NO RECORD' ? null : $serial_no;
    $invoice_no = $invoice_no === 'NO RECORD' ? null : $invoice_no;

    try {
        // Prepare SQL update query
        $stmt = $pdo->prepare("
            UPDATE applicators SET
                hp_no = :control_no,
                terminal_no = :terminal_no,
                description = :description,
                wire = :wire_type,
                terminal_maker = :terminal_maker,
                applicator_maker = :applicator_maker,
                serial_no = :serial_no,
                invoice_no = :invoice_no
            WHERE applicator_id = :applicator_id
        ");

        // Bind parameters
        $stmt->bindParam(':applicator_id', $applicator_id);
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
            return "Failed to update applicator. Please try again.";
        }
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in updateApplicator: " . $e->getMessage());
        return "Database error in updateApplicator: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}