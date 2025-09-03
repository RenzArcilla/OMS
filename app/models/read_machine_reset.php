<?php
/*
    This file defines functions that query the machine_reset table from the database.
    Used when fetching reset records for machines, e.g., for reporting or logs.

    Functions included:
    - getMachineReset($machine_id, $part_name): Retrieves all reset records for a specific machine and part.
    - getMachineResetOnTimeStamp($machine_id, $part_name, $reset_time): Retrieves a specific reset record by timestamp.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 

function getMachineReset($machine_id, $part_name) {
    /*
        Retrieve machine_reset records for a specific machine and part.

        Args:
        - $machine_id : int, 
        - $part_name: string, name of machine part

        Returns:
        - array: Associative array of machine_reset records (empty array if none)
    */

    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM machine_reset
        WHERE machine_id = :machine_id
            AND part_reset = :part_reset
        ORDER BY reset_time DESC
    ");

    $stmt->bindValue(':machine_id', $machine_id, PDO::PARAM_INT);
    $stmt->bindValue(':part_reset', $part_name, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getMachineResetOnTimeStamp($machine_id, $part_name, $reset_time) {
    /*
        Retrieve a machine_reset records for a specific machine and part.

        Args:
        - $machine_id : int, pertains to an machine
        - $part_name: string, name of machine part
        - $reset_time: time of reset

        Returns:
        - array: associative array of machine_reset records (empty array if none)
        - string: contains error message
    */

    global $pdo;

    try {
        // Prepare SQL select query with first_name and last_name
        $stmt = $pdo->prepare("
            SELECT * 
            FROM machine_reset 
            WHERE machine_id = :machine_id
                AND part_reset = :part_name
                AND reset_time = :reset_time");

        // Bind parameters
        $stmt->bindParam(':machine_id', $machine_id);
        $stmt->bindParam(':part_name', $part_name);
        $stmt->bindParam(':reset_time', $reset_time);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;

    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error in getMachineResetOnTimeStamp: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}


function getMachineResetForExport($date_range = 'quarter', $start_date = null, $end_date = null) {
    /*
        Model function to fetch machine reset records.
        This function will be used for exporting machine reset records to Excel.

        Parameters:
            str $date_range - The date range for the export (e.g., 'today', 'week', 'month', 'quarter', 'custom').
            str $start_date - The start date for the custom date range (optional).
            str $end_date - The end date for the custom date range (optional).
    */
    global $pdo;

    $query = "
        SELECT a.control_no,
            u.first_name,
            u.last_name,
            ar.reset_time,
            ar.part_reset,
            ar.previous_value
        FROM machine_reset ar
        LEFT JOIN machines a
            ON ar.machine_id = a.machine_id
        LEFT JOIN users u
            ON ar.reset_by = u.user_id
        WHERE ar.undone_by IS NULL
    ";

    // Apply date range filters
    if ($date_range === 'custom' && $start_date && $end_date) {
        $query .= " AND reset_time BETWEEN :start_date AND :end_date";
    } else {
        $date_filter = match ($date_range) {
            'today' => "DATE(reset_time) = CURDATE()",
            'week' => "YEARWEEK(reset_time, 1) = YEARWEEK(CURDATE(), 1)",
            'month' => "MONTH(reset_time) = MONTH(CURDATE()) AND YEAR(reset_time) = YEAR(CURDATE())",
            'quarter' => "QUARTER(reset_time) = QUARTER(CURDATE()) AND YEAR(reset_time) = YEAR(CURDATE())",
            default => "1=1"
        };
        $query .= " AND $date_filter";
    }

    $stmt = $pdo->prepare($query);

    // Bind parameters for custom date range
    if ($date_range === 'custom' && $start_date && $end_date) {
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}