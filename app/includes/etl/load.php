<?php

// Ensure the user is logged in before proceeding
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

// Include necessary files
require_once __DIR__ . '../../js_alert.php';
require_once __DIR__ . '/../../models/read_applicators.php';
require_once __DIR__ . '/../../models/read_machines.php';
require_once __DIR__ . '/../../models/create_record.php';
require_once __DIR__ . '/../../models/create_applicator_output.php';
require_once __DIR__ . '/../../models/create_machine_output.php';
require_once __DIR__ . '/../../models/update_monitor_applicator.php';
require_once __DIR__ . '/../../models/update_monitor_machine.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return("Invalid request method.");
    exit;
}


function loadData($data) {
    foreach ($data as $data) {
        // Prepare relevant data for database operations
        $shift = $data['Shift'] ?? null;
        $machine_id = $data['Machine No'] ?? null;
        $app1_id = $data['Applicator1'] ?? null;
        $app2_id = $data['Applicator2'] ?? null;
        $date = $data['Date'] ?? null;
        // Clarification: Same output for all (machine, app1, app2)
        $total_output = (int) $data['Output'] ?? 0;
        $app1_out = $total_output;
        $app2_out = $total_output;
        $machine_out = $total_output;


        // 0. Validate machine and applicator existence
        if (empty($app1_id)) return("Applicator 1 is required.");
        $app1_data = applicatorExists($app1_id);
        // The following line returns if the applicator doesn't exist or if the function returns an error string.
        if (!is_array($app1_data)) {
            return(is_string($app1_data) ? $app1_data : "Applicator: $app1_id not found.");
        }

        $app2_data = null;
        if (!empty($app2_id)) {
            $app2_data = applicatorExists($app2_id);
            if (!is_array($app2_data)) {
                return(is_string($app2_data) ? $app2_data : "Applicator 2: $app2_id not found.");
            }
        }

        if (empty($machine_id)) return("Machine control number is required.");
        $machine_data = machineExists($machine_id);
        if (!is_array($machine_data)) {
            return(is_string($machine_data) ? $machine_data : "Machine: $machine_id not found.");
        }

        $app1 = $app1_data['hp_no'];
        if ($app1_id === $app2_id) {
            return "Error! Duplicate applicator entry: $app1";
        }

        // 1. Create a record
        $record_id = createRecord($shift, $machine_data, $app1_data, $app2_data, $date, $_SESSION['user_id']);
        if (is_string($record_id)) {
            return($record_id);
        }
        if (!$record_id) return("Error occured when creating to create record.");


        // 2.1 Submit applicator1 output
        $app1_status = submitApplicatorOutput($app1_data, $app1_out, $record_id);
        if (is_string($app1_status)) {
            return($app1_status);
        }

        // 2.2 Submit applicator2 output, if exists
        if (!is_null($app2_id)) {
            $app2_status = submitApplicatorOutput($app2_data, $app2_out, $record_id);
            if (is_string($app2_status)) {
                return($app2_status);
            }
        }

        // 2.3 Submit machine output
        $machine_status = submitMachineOutput($machine_data, $machine_out, $record_id);
        if (is_string($machine_status)) {
            return($machine_status);
        }


        // 3.1 Update monitoring table for applicator1
        $app1_monitor_status = monitorApplicatorOutput($app1_data, $app1_out);
        if (is_string($app1_monitor_status)) {
            return($app1_monitor_status);
        }

        // 3.2 Update monitoring table for applicator2
        $app2_monitor_status = null;
        if ($app2_id) {
            $app2_monitor_status = monitorApplicatorOutput($app2_data, $app2_out);
            if (is_string($app2_monitor_status)) {
                return($app2_monitor_status);
            }
        }

        // 3.3 Update monitoring table for machine
        $machine_monitor_status = monitorMachineOutput($machine_data, $machine_out);
        if (is_string($machine_monitor_status)) {
            return($machine_monitor_status);
        }
    }

    // 4. If all operations were successful, return to the upload controller with a success message
    return("All outputs recorded successfully!");
}
