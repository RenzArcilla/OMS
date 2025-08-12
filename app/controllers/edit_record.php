<?php
/*
    This script handles the editing logic for an existing record in the database.
    It retrieves form data, sanitizes it, and updates the database record.
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Include necessary files
require_once '../includes/db.php';
require_once '../includes/js_alert.php';
include_once '../models/read_machines.php';
include_once '../models/read_applicators.php';
include_once '../models/update_record.php';
include_once '../models/update_applicator_output.php';
include_once '../models/update_machine_output.php';
include_once '../models/update_monitor_applicator.php';

// Redirect url
$redirect_url = "../views/record_output.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// 1. Sanitize input
$record_id = isset($_POST['record_id']) ? intval($_POST['record_id']) : null;
$prev_app1 = isset($_POST['prev_app1']) ? strtoupper(trim($_POST['prev_app1'])) : null;
$prev_app2 = isset($_POST['prev_app2']) ? strtoupper(trim($_POST['prev_app2'])) : null;
$prev_app1_output = isset($_POST['prev_app1_output']) ? intval(trim($_POST['prev_app1_output'])) : null;
$prev_app2_output = isset($_POST['prev_app2_output']) ? intval(trim($_POST['prev_app2_output'])) : null;
$date_inspected = isset($_POST['date_inspected']) ? strtoupper(trim($_POST['date_inspected'])) : null;
$shift = isset($_POST['shift']) ? strtoupper(trim($_POST['shift'])) : null;
$app1 = isset($_POST['app1']) ? strtoupper(trim($_POST['app1'])) : null;
$app1_output = isset($_POST['app1_output']) ? intval(trim($_POST['app1_output'])) : null;
$app2 = isset($_POST['app2']) ? strtoupper(trim($_POST['app2'])) : null;
$app2_output = isset($_POST['app2_output']) ? intval(trim($_POST['app2_output'])) : null;
$machine = isset($_POST['machine']) ? strtoupper(trim($_POST['machine'])) : null;
$machine_output = isset($_POST['machine_output']) ? intval(trim($_POST['machine_output'])) : null;

// 2. Validation
if (empty($record_id) || empty($date_inspected) || empty($shift) ||
    empty($app1) || empty($app1_output) || empty($machine) || empty($machine_output)) {
    jsAlertRedirect("Please fill in all required fields.", $redirect_url);
    exit;
}

// Validate app2_output if app2 is provided
if (!empty($app2) && empty($app2_output)) {
    jsAlertRedirect("Please provide output value for Applicator 2.", $redirect_url);
    exit;
}

if (!empty($app1) && !empty($app2) && $app1 === $app2) {
    jsAlertRedirect("Error! Duplicate applicator entry: $app1", $redirect_url);
    exit;
}

if (!in_array($shift, ['FIRST', 'SECOND', 'NIGHT'])) {
    jsAlertRedirect("Invalid selection for work shift.", $redirect_url);
    exit;
}

// 3. Database operation
// Update the record in the database

// a. Check if applicators exists
$app1_data = applicatorExists($app1);
if (!is_array($app1_data)) {
    jsAlertRedirect("Applicator: $app1 not found!", $redirect_url);
    exit;
} elseif (is_string($app1_data)) {
    jsAlertRedirect($app1_data, $redirect_url);
    exit;
}

if (!empty($app2)) {
    $app2_data = applicatorExists($app2);
    if (!is_array($app2_data)) {
        jsAlertRedirect("Applicator: $app2 not found!", $redirect_url);
        exit;
    } elseif (is_string($app2_data)) {
        jsAlertRedirect($app2_data, $redirect_url);
        exit;
    }
}

// for previous applicators (will be used to differentiate app1 and app2 in the applicator_outputs table)
$prev_app1_data = applicatorExists($prev_app1);
if (!is_array($prev_app1_data)) {
    jsAlertRedirect("Previous Applicator 1: $prev_app1 not found!", $redirect_url);
    exit;
} elseif (is_string($prev_app1_data)) {
    jsAlertRedirect($prev_app1_data, $redirect_url);
    exit;
}

$prev_app2_data = null;
if (!empty($prev_app2)) {
    $prev_app2_data = applicatorExists($prev_app2);
    if (!is_array($prev_app2_data)) {
        jsAlertRedirect("Previous Applicator 2: $prev_app2 not found!", $redirect_url);
        exit;
    } elseif (is_string($prev_app2_data)) {
        jsAlertRedirect($prev_app2_data, $redirect_url);
        exit;
    }
}

// b. Check if machine exists
$machine_data = machineExists($machine);
if (!is_array($machine_data)) {
    jsAlertRedirect("Machine: $machine not found!", $redirect_url);
    exit;
} elseif (is_string($machine_data)) {
    jsAlertRedirect($machine_data, $redirect_url);
    exit;
}

// c. Check if applicators are disabled
$app1_disabled = getInactiveApplicatorByHpNo($app1);
if ($app1_disabled) {
    jsAlertRedirect("Applicator: $app1 is disabled!", $redirect_url);
    exit;
} elseif (is_string($app1_disabled)) {
    jsAlertRedirect($app1_disabled, $redirect_url);
    exit;
}

if (!empty($app2)) {
    $app2_disabled = getInactiveApplicatorByHpNo($app2);
    if ($app2_disabled) {
        jsAlertRedirect("Applicator: $app2 is disabled!", $redirect_url);
        exit;
    } elseif (is_string($app2_disabled)) {
        jsAlertRedirect($app2_disabled, $redirect_url);
        exit;
    }
}

// d. Check if machine is disabled
$machine_disabled = getInactiveMachineByControlNo($machine);
if ($machine_disabled) {
    jsAlertRedirect("Machine: $machine is disabled!", $redirect_url);
    exit;
} elseif (is_string($machine_disabled)) {
    jsAlertRedirect($machine_disabled, $redirect_url);
    exit;
}

try {
    $pdo->beginTransaction();

    // e. Update the records table 
    $update_record_result = updateRecord(
        $record_id, 
        $date_inspected, 
        $shift, 
        $app1_data['applicator_id'], 
        !empty($app2) ? $app2_data['applicator_id'] : null, 
        $machine_data['machine_id']
    );
    if (is_string($update_record_result)) {
        throw new Exception($update_record_result);
    }

    // f. Update applicator_outputs 
    $update_app1_output_result = updateApplicatorOutput(
        $app1_data, 
        $app1_output, 
        $record_id, 
        $prev_app1_data
    );
    if (is_string($update_app1_output_result)) {
        throw new Exception($update_app1_output_result);
    }

    if (!empty($app2)) {
        $update_app2_output_result = updateApplicatorOutput(
            $app2_data, 
            $app2_output, 
            $record_id, 
            $prev_app2_data
        );
        if (is_string($update_app2_output_result)) {
            throw new Exception($update_app2_output_result);
        }
    }

    // g. Update machine_outputs 
    $update_machine_output_result = updateMachineOutput(
        $machine_data, 
        $machine_output, 
        $record_id
    );
    if (is_string($update_machine_output_result)) {
        throw new Exception($update_machine_output_result);
    }

    // h. Update cumulative applicator outputs
    // For app1
    if ($prev_app1 === $app1) {
        $difference = $app1_output - $prev_app1_output;
        if ($difference != 0) {
            // update cumulative sum with difference
            $result = monitorApplicatorOutput($app1_data, $difference);
            if (is_string($result)) {
                throw new Exception($result);
            }
        }
    } else {
        // subtract prev_app1_output from prev_app1 cumulative
        $result = monitorApplicatorOutput($prev_app1_data, -$prev_app1_output);
        if (is_string($result)) {
            throw new Exception($result);
        }
        // add app1_output to app1 cumulative
        $result = monitorApplicatorOutput($app1_data, $app1_output);
        if (is_string($result)) {
            throw new Exception($result);
        }
    }

    // For app2
    if (!is_null($prev_app2) && !is_null($app2)) {
        if ($prev_app2 === $app2) {
            $difference = $app2_output - $prev_app2_output;
            if ($difference != 0) {
                // update cumulative sum with difference
                $result = monitorApplicatorOutput($app2_data, $difference);
                if (is_string($result)) {
                    throw new Exception($result);
                }
            }
        } else {
            // Removing old app2 output
            $result = monitorApplicatorOutput($prev_app2_data, -$prev_app2_output);
            if (is_string($result)) {
                throw new Exception($result);
            }
            // Adding new app2 output
            $result = monitorApplicatorOutput($app2_data, $app2_output);
            if (is_string($result)) {
                throw new Exception($result);
            }
        }
    } elseif (is_null($prev_app2) && !is_null($app2)) {
        // Adding new app2 output
        $result = monitorApplicatorOutput($app2_data, $app2_output);
        if (is_string($result)) {
            throw new Exception($result);
        }
    } elseif (!is_null($prev_app2) && is_null($app2)) {
        // Removing old app2 output
        $result = monitorApplicatorOutput($prev_app2_data, -$prev_app2_output);
        if (is_string($result)) {
            throw new Exception($result);
        }
    }

    $pdo->commit();
    jsAlertRedirect("Record updated successfully!", $redirect_url);
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsAlertRedirect("Update failed: " . $e->getMessage(), $redirect_url);
    exit;
}