<?php 
/* 
    This file counts the number of active machines in the database and returns the count as an integer. 
    Currently used in the dashboard to display the number of active machines. 
*/

// Include necessary files
require_once '../models/read_machines.php';

try {
    $result = countActiveMachines();
    if (!is_int($result)) {
        throw new Exception("Invalid result from countActiveMachines");
    } else {
        return $result; // Output the count of active machines
    }   
} catch (PDOException $e) {
    error_log("Error counting active machines: " . $e->getMessage());
    return "Error!";
} catch (Exception $e) {
    error_log("Error counting active machines: " . $e->getMessage());
    return "Error!";
}