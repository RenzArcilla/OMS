<?php
/*
    This script handles the CREATE operation for submitting a new record in the records database.
    It includes a function to insert record data into the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';

function createRecord($shift, $machine_data, $applicator_data, $date_inspected, $created_by) {
    /*
    Function to create a new record in the records table.
    
    Args:
    - $shift: String representing the shift ('1st', '2nd', 'NIGHT')
    - $machine_data: Array containing machine information from database
    - $applicator_data: Array containing applicator information from database
    - $date_inspected: String date in Y-m-d format
    - $created_by: Integer user ID of the person creating the record
    
    Returns:
    - Integer record_id on success
    - String containing error message and redirect using JS <alert> on failure
    */
    
    global $pdo;
    
    try {
        // Convert shift format to match database enum
        $shift_formatted = '';
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
                return "<script>
                    alert('Invalid shift value: " . htmlspecialchars($shift, ENT_QUOTES) . "');
                    window.location.href = '../templates/record_output.php';</script>";
        }
        
        // Get machine and applicator IDs
        $machine_id = $machine_data['machine_id'];
        $applicator_id = $applicator_data['applicator_id'];
        
        // Prepare the SQL statement
        $stmt = $pdo->prepare("
            INSERT INTO records 
            (shift, machine_id, applicator_id, created_by, date_inspected) 
            VALUES 
            (:shift, :machine_id, :applicator_id, :created_by, :date_inspected)
        ");
        
        // Bind parameters
        $stmt->bindParam(':shift', $shift_formatted, PDO::PARAM_STR);
        $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
        $stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
        $stmt->bindParam(':created_by', $created_by, PDO::PARAM_INT);
        $stmt->bindParam(':date_inspected', $date_inspected, PDO::PARAM_STR);
        
        // Execute the query
        $stmt->execute();
        
        // Return the newly created record ID
        return $pdo->lastInsertId();
        
    } catch (PDOException $e) {
        // Log error and return error message
        error_log("Database Error in createRecord: " . $e->getMessage());
        return "<script>
            alert('Database error occurred while creating record: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "');
            window.location.href = '../templates/record_output.php';</script>";
    }
}
?>