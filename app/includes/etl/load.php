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

// --- Redirect path ---
$redirect = "../../views/file_upload.php";

// --- Helper: Fail during error ---
function fail($msg) {
    jsAlertRedirect($msg, $redirect);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail("Invalid request method.");
    exit;
}

// --- Helper: Get Inputs ---
function getInput($key) {
    return strtoupper(trim($_POST[$key] ?? ''));
}


function loadData($data) {
    foreach ($data as $data) {
        // Prepare relevant data for database operations
        $total_output = (int) $data['Output'] ?? 0;
        $app1_out = $total_output;
        $app2_out = $total_output;
        $machine_out = $total_output;
        $shift = $data['Shift'] ?? null;
        $machine_id = $data['Machine No'] ?? null;
        $app1_id = $data['Applicator1'] ?? null;
        $app2_id = $data['Applicator2'] ?? null;
        $date = $data['Date'] ?? null;

        // 1. Create the record
        $record_id = createRecord($shift, $machine_id, $app1_id, $app2_id, $date, $_SESSION['user_id']);
        if (is_string($record_id)) {
            fail($record_id);
        }
        if (!$record_id) fail("Failed to create record.");

        // 2.1 Submit applicator1 output
        $app1_status = submitApplicatorOutput($app1_id, $app1_out, $record_id);
        if (is_string($app1_status)) {
            fail($app1_status);
        }

        // 2.2 Submit applicator2 output
        $app2_status = submitApplicatorOutput($app2_id, $app2_out, $record_id);
        if (is_string($app2_status)) {
            fail($app2_status);
        }

        // 2.3 Submit machine output
        $machine_status = submitMachineOutput($machine_id, $machine_out, $record_id);
        if (is_string($machine_status)) {
            fail($machine_status);
        }

        // 3.1 Update monitoring table for applicator1
        $app1_monitor_status = monitorApplicatorOutput($app1_id, $app1_out);
        if (is_string($app1_monitor_status)) {
            fail($app1_monitor_status);
        }

        // 3.2 Update monitoring table for applicator2
        $app2_monitor_status = null;
        if ($app2_id) {
            $app2_monitor_status = monitorApplicatorOutput($app2_id, $app2_out);
            if (is_string($app2_monitor_status)) {
                fail($app2_monitor_status);
            }
        }

        // 3.3 Update monitoring table for machine
        $machine_monitor_status = monitorMachineOutput($machine_id, $machine_out);
        if (is_string($machine_monitor_status)) {
            fail($machine_monitor_status);
        }

        // 4. If all operations were successful, redirect to the record output page with success message
        jsAlertRedirect("All outputs recorded successfully!", $redirect);
    }
}
