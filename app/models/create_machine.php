<?php
/*
    This script handles the CREATE operation for machines in the database.
    It includes a function to insert machine data into the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function createMachine($control_no, $description, $model,
                          $machine_maker, $serial_no, $invoice_no) {
    global $pdo;

    /*
    Function to create a new machine in the database.
    
    Args:
    - $control_no: Control number of the machine.
    - $description: Description of the machine (AUTOMATIC/SEMI-AUTOMATIC).
    - $model: Model of the machine.
    - $machine_maker: Maker of the machine.
    - $serial_no: Serial number of the applicator.
    - $invoice_no: Invoice number associated with the applicator.
                
    Returns:
    - true on successful operation.
    - string containing error message and redirect using JS <alert>.
    */

    
    // Set serial_no and invoice_no to null if empty or "NO RECORD"
    $serial_no = $serial_no === 'NO RECORD' ? null : $serial_no;
    $invoice_no = $invoice_no === 'NO RECORD' ? null : $invoice_no;

    try {
        // Prepare SQL insert query
        $stmt = $pdo->prepare("
            INSERT INTO machines (control_no, description, model, 
                                     maker, serial_no, invoice_no)
            VALUES (:control_no, :description, :model,
                    :machine_maker, :serial_no, :invoice_no)
        ");

        // Bind parameters
        $stmt->bindParam(':control_no', $control_no);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':machine_maker', $machine_maker);
        $stmt->bindParam(':serial_no', $serial_no, PDO::PARAM_NULL | PDO::PARAM_STR);
        $stmt->bindParam(':invoice_no', $invoice_no,    PDO::PARAM_NULL | PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "<script>alert('Failed to add machine. Please try again.');
                window.location.href = '../views/add_entry.php';</script>";
        }
    } catch (PDOException $e) {
        // Log error and return error message
        error_log("Database Error: " . $e->getMessage());
        return "<script>
            alert('Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "');
            window.location.href = '../views/add_entry.php';</script>";
    }
}