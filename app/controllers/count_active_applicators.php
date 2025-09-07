<?php 
/* 
    This file counts the number of active applicators in the database and returns the count as an integer. 
    Currently used in the dashboard to display the number of active applicators. 
*/

// Include necessary files
require_once '../models/read_applicators.php';

try {
    $result = countActiveApplicators();
    if (!is_int($result)) {
        throw new Exception("Invalid result from countActiveApplicators");
    } else {
        return $result; // Output the count of active applicators
    }   
} catch (PDOException $e) {
    error_log("Error counting active applicators: " . $e->getMessage());
    return "Error!";
} catch (Exception $e) {
    error_log("Error counting active applicators: " . $e->getMessage());
    return "Error!";
}