<?php
/*
    This script handles the submission of a new output record to the system.
    It retrieves form data, sanitizes it, and inserts it into the database.
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../templates/login.php");
    exit();
}

// Retrieve form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $date_inspected = isset($_POST['date_inspected']) ? trim($_POST['date_inspected']) : null;
    $shift = isset($_POST['shift']) ? strtoupper(trim($_POST['shift'])) : null;
    $app1 = isset($_POST['app1']) ? strtoupper(trim($_POST['app1'])) : null;
    $app1_output = isset($_POST['app1_output']) ? strtoupper(trim($_POST['app1_output'])) : null;
    $app2 = empty($_POST['app2']) ? 'NO RECORD' : strtoupper(trim($_POST['app2']));
    $app2_output = empty($_POST['app2_output']) ? 'NO RECORD' : strtoupper(trim($_POST['app2_output']));
    $machine = isset($_POST['machine']) ? strtoupper(trim($_POST['machine'])) : null;
    $machine_output = isset($_POST['machine_output']) ? strtoupper(trim($_POST['machine_output'])) : null;
    
    // Check if fields are empty
    if (empty($date_inspected) || empty($shift) || empty($app1) || empty($app1_output) || empty($machine) || empty($machine_output)) {
        echo "<script>alert('Please fill in all required fields.');
            window.location.href = '../templates/record_output.php';</script>";

    // Reg-ex to verify date format
    } else if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_inspected)) {
        // Invalid format
        echo "<script>alert('Invalid date format.');
            window.location.href = '../templates/record_output.php';</script>";    

    // Verify valid 'shift' selection
    } else if ($shift !== 'FIRST' && $shift !== 'SECOND' && $shift !== 'NIGHT') {
        echo "<script>alert('Invalid selection for description.');
            window.location.href = '../templates/record_output.php';</script>";

    } else {

        // Convert the date input to DateTime format
        try {
            $dateObj = new DateTime($date_inspected); // Fixed variable name
            $sanitizedDate = $dateObj->format('Y-m-d');
        } catch (Exception $e) {
            echo "<script>alert('Invalid date value.');
                window.location.href = '../templates/record_output.php';</script>";
            exit(); // Add exit to stop execution
        }

        // Check if applicators exists 
        include_once '../models/READ_applicators.php';
        $app1_result = applicatorExists($app1);
        if (is_string($app1_result)) { // Fixed variable name
            echo $app1_result;
            exit(); // Add exit to stop execution
        }

        $app2_result = null; // Initialize variable
        if ($app2 !== "NO RECORD") {
            $app2_result = applicatorExists($app2);
            if (is_string($app2_result)) {
                echo $app2_result;
                exit(); // Add exit to stop execution
            }
        }

        // Check if machine exists
        include_once '../models/READ_machines.php';
        $machine_results = machineExists($machine);
        if (is_string($machine_results)) {
            echo $machine_results;
            exit(); // Add exit to stop execution
        }
        
        // Try to create a record 
        include_once '../models/CREATE_record.php';
        $record_id = createRecord($shift, $machine_results, $app1_result, $sanitizedDate, $_SESSION['user_id']);

        // Try to submit outputs
        include_once '../models/CREATE_applicator_output.php';
        $app1_submit_result = submitApplicatorOutput($app1_result, $app1_output, $record_id);


        // Try to submit the output of second applicator (if exists)
        if ($app2 !== "NO RECORD") {
            $app2_submit_result = submitApplicatorOutput($app2_result, $app2_output, $record_id);
        }

        // Try to submit the output of the machine
        include_once '../models/CREATE_machine_output.php';
        $machine_submit_result = submitMachineOutput($machine_results, $machine_output, $record_id);

        // If we get here, everything succeeded
        if ($record_id && $app1_submit_result === true && $machine_submit_result === true) {
            echo "<script>alert('All outputs recorded successfully!');
                window.location.href = '../templates/record_output.php';</script>";
        } else {
            echo "<script>alert('An unexpected error occurred. Please try again.');
                window.location.href = '../templates/record_output.php';</script>";
        }
    }
}