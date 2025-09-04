<?php
/*
    Controller: Handles the processing of uploaded production data with batched DB operations.
    Enhanced with comprehensive debugging and error handling.
*/

// Include necessary files
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/js_alert.php';
require_once __DIR__ . '/../../models/read_applicators.php';
require_once __DIR__ . '/../../models/read_machines.php';
require_once __DIR__ . '/../../models/create_record.php';
require_once __DIR__ . '/../../models/create_applicator_output.php';
require_once __DIR__ . '/../../models/create_machine_output.php';
require_once __DIR__ . '/../../models/update_monitor_applicator.php';
require_once __DIR__ . '/../../models/update_monitor_machine.php';

// Enable detailed error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/load_debug.log');

function debugLog($message) {
    error_log("[DEBUG " . date('Y-m-d H:i:s') . "] " . $message);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    debugLog("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    return("Invalid request method.");
    exit;
}

function loadData($data) {
    debugLog("=== STARTING loadData function ===");
    debugLog("Input data count: " . (is_array($data) ? count($data) : 'NOT_ARRAY'));
    debugLog("Sample input data: " . json_encode(array_slice($data, 0, 1)));
    
    global $pdo;
    
    try {
        // Input validation
        if (empty($data) || !is_array($data)) {
            debugLog("ERROR: No valid data provided");
            return "No valid data provided for processing.";
        }
        
        if (!isset($_SESSION['user_id'])) {
            debugLog("ERROR: No user session found");
            return "User session not found. Please log in again.";
        }
        
        debugLog("User ID: " . $_SESSION['user_id']);
        
        // Test database connection
        if (!$pdo) {
            debugLog("ERROR: PDO connection not available");
            return "Database connection error.";
        }
        
        // Test a simple query
        try {
            $test = $pdo->query("SELECT 1");
            debugLog("Database connection test: SUCCESS");
        } catch (Exception $e) {
            debugLog("ERROR: Database connection test failed: " . $e->getMessage());
            return "Database connection failed: " . $e->getMessage();
        }
        
        // Step 1: Collect all unique machines and applicators for batch validation
        $unique_machines = [];
        $unique_applicators = [];
        
        debugLog("=== Step 1: Collecting unique IDs ===");
        
        foreach ($data as $index => $row) {
            debugLog("Processing row $index: " . json_encode($row));
            
            // Validate required fields for each row
            if (empty($row['Machine No'])) {
                debugLog("ERROR: Row $index missing Machine No");
                return "Row " . ($index + 1) . ": Machine control number is required.";
            }
            
            if (empty($row['Applicator 1'])) {
                debugLog("ERROR: Row $index missing Applicator 1");
                return "Row " . ($index + 1) . ": Applicator 1 is required.";
            }
            
            if (empty($row['Date'])) {
                debugLog("ERROR: Row $index missing Date");
                return "Row " . ($index + 1) . ": Date is required.";
            }
            
            if (empty($row['Shift'])) {
                debugLog("ERROR: Row $index missing Shift");
                return "Row " . ($index + 1) . ": Shift is required.";
            }
            
            if (!isset($row['Output']) || !is_numeric($row['Output'])) {
                debugLog("ERROR: Row $index invalid Output: " . ($row['Output'] ?? 'NULL'));
                return "Row " . ($index + 1) . ": Valid output quantity is required.";
            }
            
            $machine_id = trim($row['Machine No']);
            $app1_id = trim($row['Applicator 1']);
            $app2_id = !empty($row['Applicator 2']) ? trim($row['Applicator 2']) : null;
            
            // Collect unique IDs
            $unique_machines[$machine_id] = true;
            $unique_applicators[$app1_id] = true;
            if ($app2_id) {
                $unique_applicators[$app2_id] = true;
            }
            
            // Check for duplicate applicators in same row
            if ($app2_id && $app1_id === $app2_id) {
                debugLog("ERROR: Row $index has duplicate applicators: $app1_id");
                return "Row " . ($index + 1) . ": Duplicate applicator entry: $app1_id";
            }
        }
        
        debugLog("Unique machines: " . implode(', ', array_keys($unique_machines)));
        debugLog("Unique applicators: " . implode(', ', array_keys($unique_applicators)));
        
        // Step 2: Batch check machine existence
        debugLog("=== Step 2: Checking machine existence ===");
        $machine_data_cache = [];
        if (!empty($unique_machines)) {
            $result = batchCheckMachines(array_keys($unique_machines));
            if (is_string($result)) {
                debugLog("ERROR: batchCheckMachines failed: $result");
                return $result; // Error occurred
            }
            $machine_data_cache = $result;
            debugLog("Found " . count($machine_data_cache) . " machines in database");
        }
        
        // Step 3: Batch check applicator existence
        debugLog("=== Step 3: Checking applicator existence ===");
        $applicator_data_cache = [];
        if (!empty($unique_applicators)) {
            $result = batchCheckApplicators(array_keys($unique_applicators));
            if (is_string($result)) {
                debugLog("ERROR: batchCheckApplicators failed: $result");
                return $result; // Error occurred
            }
            $applicator_data_cache = $result;
            debugLog("Found " . count($applicator_data_cache) . " applicators in database");
        }
        
        // Step 4: Validate all required machines and applicators exist
        debugLog("=== Step 4: Validating existence ===");
        $missing_machines = [];
        $missing_applicators = [];
        
        foreach ($data as $index => $row) {
            $machine_id = trim($row['Machine No']);
            $app1_id = trim($row['Applicator 1']);
            $app2_id = !empty($row['Applicator 2']) ? trim($row['Applicator 2']) : null;
            
            if (!isset($machine_data_cache[$machine_id])) {
                $missing_machines[] = $machine_id;
            }
            
            if (!isset($applicator_data_cache[$app1_id])) {
                $missing_applicators[] = $app1_id;
            }
            
            if ($app2_id && !isset($applicator_data_cache[$app2_id])) {
                $missing_applicators[] = $app2_id;
            }
        }
        
        // Report missing items
        if (!empty($missing_machines)) {
            $error = "Machine(s) not found in database: " . implode(', ', array_unique($missing_machines));
            debugLog("ERROR: $error");
            return $error;
        }
        
        if (!empty($missing_applicators)) {
            $error = "Applicator(s) not found in database: " . implode(', ', array_unique($missing_applicators));
            debugLog("ERROR: $error");
            return $error;
        }
        
        // Step 5: Prepare batch data for records creation
        debugLog("=== Step 5: Preparing record data ===");
        $records_data = [];
        foreach ($data as $row) {
            $shift = $row['Shift'];
            $machine_id = trim($row['Machine No']);
            $app1_id = trim($row['Applicator 1']);
            $app2_id = !empty($row['Applicator 2']) ? trim($row['Applicator 2']) : null;
            $date = $row['Date'];
            $total_output = (int) $row['Output'];
            
            $machine_data = $machine_data_cache[$machine_id];
            $app1_data = $applicator_data_cache[$app1_id];
            $app2_data = $app2_id ? $applicator_data_cache[$app2_id] : null;
            
            $records_data[] = [
                'shift' => $shift,
                'machine_data' => $machine_data,
                'app1_data' => $app1_data,
                'app2_data' => $app2_data,
                'date' => $date,
                'output' => $total_output
            ];
        }
        
        debugLog("Prepared " . count($records_data) . " records for insertion");
        
        // Step 6: Batch create records
        debugLog("=== Step 6: Creating records ===");
        $record_ids = batchCreateRecords($records_data, $_SESSION['user_id']);
        if (is_string($record_ids)) {
            debugLog("ERROR: batchCreateRecords failed: $record_ids");
            return $record_ids; // Error occurred
        }
        
        debugLog("Created records with IDs: " . implode(', ', $record_ids));
        
        // Step 7: Prepare batch data for outputs
        $machine_outputs = [];
        $applicator_outputs = [];
        $monitor_machine_data = [];
        $monitor_applicator_data = [];
        
        for ($i = 0; $i < count($records_data); $i++) {
            $record_id = $record_ids[$i];
            $row_data = $records_data[$i];
            $output = $row_data['output'];
            
            // Machine outputs
            $machine_outputs[] = [
                'record_id' => $record_id,
                'machine_data' => $row_data['machine_data'],
                'output' => $output
            ];
            
            // Applicator outputs
            $applicator_outputs[] = [
                'record_id' => $record_id,
                'applicator_data' => $row_data['app1_data'],
                'output' => $output
            ];
            
            if ($row_data['app2_data']) {
                $applicator_outputs[] = [
                    'record_id' => $record_id,
                    'applicator_data' => $row_data['app2_data'],
                    'output' => $output
                ];
            }
            
            // Monitor data
            $monitor_machine_data[] = [
                'machine_data' => $row_data['machine_data'],
                'output' => $output
            ];
            
            $monitor_applicator_data[] = [
                'applicator_data' => $row_data['app1_data'],
                'output' => $output
            ];
            
            if ($row_data['app2_data']) {
                $monitor_applicator_data[] = [
                    'applicator_data' => $row_data['app2_data'],
                    'output' => $output
                ];
            }
        }
        
        // Step 8: Batch submit outputs
        debugLog("=== Step 8: Submitting outputs ===");
        $result = batchSubmitMachineOutputs($machine_outputs);
        if (is_string($result)) {
            debugLog("ERROR: batchSubmitMachineOutputs failed: $result");
            return $result;
        }
        
        $result = batchSubmitApplicatorOutputs($applicator_outputs);
        if (is_string($result)) {
            debugLog("ERROR: batchSubmitApplicatorOutputs failed: $result");
            return $result;
        }
        
        // Step 9: Batch update monitoring tables
        debugLog("=== Step 9: Updating monitors ===");
        $result = batchUpdateMonitorMachine($monitor_machine_data);
        if (is_string($result)) {
            debugLog("ERROR: batchUpdateMonitorMachine failed: $result");
            return $result;
        }
        
        $result = batchUpdateMonitorApplicator($monitor_applicator_data);
        if (is_string($result)) {
            debugLog("ERROR: batchUpdateMonitorApplicator failed: $result");
            return $result;
        }
        
        debugLog("=== SUCCESS: All operations completed ===");
        return "All outputs recorded successfully! Processed " . count($data) . " records.";
        
    } catch (Exception $e) {
        debugLog("EXCEPTION: " . $e->getMessage() . " in " . $e->getFile() . " line " . $e->getLine());
        error_log("LoadData Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

function batchCreateRecords($records_data, $created_by) {
    debugLog("=== batchCreateRecords START ===");
    debugLog("Records to create: " . count($records_data));
    debugLog("Created by user ID: $created_by");
    
    global $pdo;
    
    try {
        if (empty($records_data)) {
            debugLog("ERROR: No records data provided");
            return "No records data provided";
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO records 
            (shift, machine_id, applicator1_id, applicator2_id, created_by, date_inspected, date_encoded) 
            VALUES 
            (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");
        
        if (!$stmt) {
            $error = "Failed to prepare statement: " . implode(', ', $pdo->errorInfo());
            debugLog("ERROR: $error");
            return $error;
        }
        
        debugLog("Statement prepared successfully");
        
        $record_ids = [];
        
        foreach ($records_data as $index => $row) {
            debugLog("Processing record $index");
            
            // Convert shift format
            switch (strtoupper($row['shift'])) {
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
                    $error = "Invalid shift value: " . htmlspecialchars($row['shift'], ENT_QUOTES);
                    debugLog("ERROR: $error");
                    return $error;
            }
            
            $machine_id = $row['machine_data']['machine_id'];
            $app1_id = $row['app1_data']['applicator_id'];
            $app2_id = $row['app2_data'] ? $row['app2_data']['applicator_id'] : null;
            
            debugLog("Record $index data: shift=$shift_formatted, machine_id=$machine_id, app1_id=$app1_id, app2_id=$app2_id, date={$row['date']}");
            
            $success = $stmt->execute([
                $shift_formatted,
                $machine_id,
                $app1_id,
                $app2_id,
                $created_by,
                $row['date']
            ]);
            
            if (!$success) {
                $errorInfo = $stmt->errorInfo();
                $error = "Failed to create record $index: " . $errorInfo[2];
                debugLog("ERROR: $error");
                return $error;
            }
            
            $lastId = $pdo->lastInsertId();
            if (!$lastId) {
                $error = "Failed to get record ID after insert for record $index";
                debugLog("ERROR: $error");
                return $error;
            }
            
            $record_ids[] = (int) $lastId;
            debugLog("Record $index created with ID: $lastId");
        }
        
        if ($pdo->inTransaction()) {
            debugLog("Transaction committed for batchCreateRecords");
        }
        return $record_ids;
        
    } catch (PDOException $e) {
        debugLog("PDO EXCEPTION: " . $e->getMessage());
        error_log("Database Error in batchCreateRecords: " . $e->getMessage());
        return "Database error creating records: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

function batchCheckMachines($machine_ids) {
    debugLog("=== batchCheckMachines START ===");
    debugLog("Machine IDs to check: " . implode(', ', $machine_ids));
    
    global $pdo;
    
    try {
        if (empty($machine_ids)) {
            debugLog("No machine IDs provided");
            return [];
        }
        
        $placeholders = str_repeat('?,', count($machine_ids) - 1) . '?';
        $sql = "SELECT * FROM machines WHERE control_no IN ($placeholders)";
        debugLog("SQL: $sql");
        debugLog("Parameters: " . implode(', ', $machine_ids));
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($machine_ids);
        
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['control_no']] = $row;
            debugLog("Found machine: " . $row['control_no'] . " (ID: " . $row['machine_id'] . ")");
        }
        
        debugLog("=== batchCheckMachines SUCCESS ===");
        return $results;
        
    } catch (PDOException $e) {
        debugLog("PDO EXCEPTION in batchCheckMachines: " . $e->getMessage());
        error_log("Database Error in batchCheckMachines: " . $e->getMessage());
        return "Database error checking machines: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

function batchCheckApplicators($applicator_ids) {
    debugLog("=== batchCheckApplicators START ===");
    debugLog("Applicator IDs to check: " . implode(', ', $applicator_ids));
    
    global $pdo;
    
    try {
        if (empty($applicator_ids)) {
            debugLog("No applicator IDs provided");
            return [];
        }
        
        $placeholders = str_repeat('?,', count($applicator_ids) - 1) . '?';
        $sql = "SELECT * FROM applicators WHERE hp_no IN ($placeholders)";
        debugLog("SQL: $sql");
        debugLog("Parameters: " . implode(', ', $applicator_ids));
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($applicator_ids);
        
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['hp_no']] = $row;
            debugLog("Found applicator: " . $row['hp_no'] . " (ID: " . $row['applicator_id'] . ")");
        }
        
        debugLog("=== batchCheckApplicators SUCCESS ===");
        return $results;
        
    } catch (PDOException $e) {
        debugLog("PDO EXCEPTION in batchCheckApplicators: " . $e->getMessage());
        error_log("Database Error in batchCheckApplicators: " . $e->getMessage());
        return "Database error checking applicators: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

// Placeholder functions for the missing batch operations
function batchSubmitMachineOutputs($machine_outputs) {
    debugLog("=== batchSubmitMachineOutputs START ===");
    debugLog("Machine outputs to submit: " . count($machine_outputs));
    global $pdo;
    if (empty($machine_outputs)) { return true; }
    try {
        $placeholders = [];
        $params = [];
        foreach ($machine_outputs as $row) {
            $record_id = (int)$row['record_id'];
            $machine_id = (int)$row['machine_data']['machine_id'];
            $val = (int)$row['output'];
            $placeholders[] = "(?, ?, ?, ?, ?, ?, NULL)";
            array_push($params, $record_id, $machine_id, $val, $val, $val, $val);
        }
        $sql = "INSERT INTO machine_outputs (record_id, machine_id, total_machine_output, cut_blade, strip_blade_a, strip_blade_b, custom_parts) VALUES " . implode(',', $placeholders);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        debugLog("=== batchSubmitMachineOutputs SUCCESS ===");
        return true;
    } catch (PDOException $e) {
        error_log("DB Error in batchSubmitMachineOutputs: " . $e->getMessage());
        return "Database error in batchSubmitMachineOutputs: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

function batchSubmitApplicatorOutputs($applicator_outputs) {
    debugLog("=== batchSubmitApplicatorOutputs START ===");
    debugLog("Applicator outputs to submit: " . count($applicator_outputs));
    global $pdo;
    if (empty($applicator_outputs)) { return true; }
    try {
        $sideRows = [];
        $endRows = [];
        $sideParams = [];
        $endParams = [];
        foreach ($applicator_outputs as $row) {
            $record_id = (int)$row['record_id'];
            $applicator = $row['applicator_data'];
            $applicator_id = (int)$applicator['applicator_id'];
            $type = trim($applicator['description']);
            $val = (int)$row['output'];
            if ($type === 'SIDE') {
                $sideRows[] = "(?, ?, ?, ?, ?, ?, ?, ?, NULL)";
                array_push($sideParams, $record_id, $applicator_id, $val, $val, $val, $val, $val, $val);
            } else {
                $endRows[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)";
                array_push($endParams, $record_id, $applicator_id, $val, $val, $val, $val, $val, $val, $val, $val);
            }
        }
        if (!empty($sideRows)) {
            $sqlSide = "INSERT INTO applicator_outputs (record_id, applicator_id, total_output, wire_crimper, wire_anvil, insulation_crimper, insulation_anvil, slide_cutter, cutter_holder, custom_parts) VALUES " . implode(',', $sideRows);
            $stmtSide = $pdo->prepare($sqlSide);
            $stmtSide->execute($sideParams);
        }
        if (!empty($endRows)) {
            $sqlEnd = "INSERT INTO applicator_outputs (record_id, applicator_id, total_output, wire_crimper, wire_anvil, insulation_crimper, insulation_anvil, shear_blade, cutter_a, cutter_b, custom_parts) VALUES " . implode(',', $endRows);
            $stmtEnd = $pdo->prepare($sqlEnd);
            $stmtEnd->execute($endParams);
        }
        debugLog("=== batchSubmitApplicatorOutputs SUCCESS ===");
        return true;
    } catch (PDOException $e) {
        error_log("DB Error in batchSubmitApplicatorOutputs: " . $e->getMessage());
        return "Database error in batchSubmitApplicatorOutputs: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

function batchUpdateMonitorMachine($monitor_data) {
    debugLog("=== batchUpdateMonitorMachine START ===");
    debugLog("Monitor machine data to update: " . count($monitor_data));
    global $pdo;
    if (empty($monitor_data)) { return true; }
    $agg = [];
    foreach ($monitor_data as $row) {
        $id = (int)$row['machine_data']['machine_id'];
        $val = (int)$row['output'];
        $agg[$id] = ($agg[$id] ?? 0) + $val;
    }
    try {
        $stmt = $pdo->prepare("\n            INSERT INTO monitor_machine (machine_id, total_machine_output, cut_blade_output, strip_blade_a_output, strip_blade_b_output, custom_parts_output)\n            VALUES (:id, :v, :v, :v, :v, NULL)\n            ON DUPLICATE KEY UPDATE\n                total_machine_output = total_machine_output + VALUES(total_machine_output),\n                cut_blade_output = cut_blade_output + VALUES(cut_blade_output),\n                strip_blade_a_output = strip_blade_a_output + VALUES(strip_blade_a_output),\n                strip_blade_b_output = strip_blade_b_output + VALUES(strip_blade_b_output),\n                last_updated = CURRENT_TIMESTAMP\n        ");
        foreach ($agg as $id => $sum) {
            $stmt->execute([':id' => $id, ':v' => $sum]);
        }
        debugLog("=== batchUpdateMonitorMachine SUCCESS ===");
        return true;
    } catch (PDOException $e) {
        error_log("DB Error in batchUpdateMonitorMachine: " . $e->getMessage());
        return "Database error in batchUpdateMonitorMachine: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

function batchUpdateMonitorApplicator($monitor_data) {
    debugLog("=== batchUpdateMonitorApplicator START ===");
    debugLog("Monitor applicator data to update: " . count($monitor_data));
    global $pdo;
    if (empty($monitor_data)) { return true; }
    $agg = [];
    foreach ($monitor_data as $row) {
        $id = (int)$row['applicator_data']['applicator_id'];
        $val = (int)$row['output'];
        $agg[$id] = ($agg[$id] ?? 0) + $val;
    }
    try {
        $stmt = $pdo->prepare("\n            INSERT INTO monitor_applicator (applicator_id, total_output, wire_crimper_output, wire_anvil_output, insulation_crimper_output, insulation_anvil_output, custom_parts_output)\n            VALUES (:id, :v, :v, :v, :v, :v, NULL)\n            ON DUPLICATE KEY UPDATE\n                total_output = total_output + VALUES(total_output),\n                wire_crimper_output = wire_crimper_output + VALUES(wire_crimper_output),\n                wire_anvil_output = wire_anvil_output + VALUES(wire_anvil_output),\n                insulation_crimper_output = insulation_crimper_output + VALUES(insulation_crimper_output),\n                insulation_anvil_output = insulation_anvil_output + VALUES(insulation_anvil_output),\n                last_updated = CURRENT_TIMESTAMP\n        ");
        foreach ($agg as $id => $sum) {
            $stmt->execute([':id' => $id, ':v' => $sum]);
        }
        debugLog("=== batchUpdateMonitorApplicator SUCCESS ===");
        return true;
    } catch (PDOException $e) {
        error_log("DB Error in batchUpdateMonitorApplicator: " . $e->getMessage());
        return "Database error in batchUpdateMonitorApplicator: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

// Test function
function testLoadData() {
    debugLog("=== TESTING loadData with sample data ===");
    
    $testData = [
        [
            'Machine No' => 'TEST001',
            'Applicator 1' => 'APP001',
            'Applicator 2' => null,
            'Date' => '2025-01-09',
            'Shift' => 'FIRST',
            'Output' => 100
        ]
    ];
    
    $result = loadData($testData);
    debugLog("Test result: $result");
    return $result;
}
?>