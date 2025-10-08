<?php
/*
    Batch Loader for Production Data

    Efficiently loads transformed production data into the database, minimizing reads and inserts:
    - Batch existence checks
    - Batch inserts for records, applicator outputs, machine outputs
    - Aggregate monitoring updates
    - Single transaction for atomicity
*/

// Import Required Modules
require_once __DIR__ . '/../../models/read_custom_parts.php';
require_once __DIR__ . '/../../models/read_machines.php';
require_once __DIR__ . '/../../models/read_applicators.php';
require_once __DIR__ . '/../../models/update_monitor_machine.php';
require_once __DIR__ . '/../../models/update_monitor_applicator.php';
require_once __DIR__ . '/../../models/create_record.php';
require_once __DIR__ . '/../../models/create_applicator_output.php';
require_once __DIR__ . '/../../models/create_machine_output.php';
require_once __DIR__ . '/../../models/update_monitor_applicator.php';
require_once __DIR__ . '/../../models/update_monitor_machine.php';


function LoadData(array $rows): array {
    /*
        Function: LoadData
        Purpose: Load transformed production data into the database
                using reliable operations. Also updates aggregates outputs for monitoring.

        Parameters:
            - rows (array): Transformed production data rows to be loaded.
        Returns:
            - array: Result of the load operation with success status and messages.
    */

    global $pdo;

    // Pre-check
    if (empty($rows)) {
        return ['success' => false, 'message' => 'No data rows provided.'];
    }

    // 1. Extract unique machine and applicator identifiers
    $uniqueMachines = [];
    $uniqueApplicators = [];
    foreach ($rows as $idx => $r) {
        // Remove spaces, convert to string, assign to '' if null
        $machineNo = trim((string)($r['Machine No'] ?? ''));
        $app1      = trim((string)($r['Applicator1'] ?? ''));
        $app2      = trim((string)($r['Applicator2'] ?? ''));

        // Append each to the array, ensuring no duplicates
        if ($machineNo !== '') $uniqueMachines[$machineNo] = true;
        if ($app1 !== '') $uniqueApplicators[$app1] = true;
        if ($app2 !== '' && $app2 !== $app1) $uniqueApplicators[$app2] = true;
    }
    
    // Flatten the assoc array into a plain array of unique values
    $uniqueMachines     = array_keys($uniqueMachines);
    $uniqueApplicators  = array_keys($uniqueApplicators);


    try {
        // 2. Fetch all machines
            /* Format
                [
                    'CTRL001' => ['control_no' => 'CTRL001', 'machine_name' => 'Machine A', ...],
                    ...
                ]
            */
        $machinesMap = fetchMachinesByControlNos($uniqueMachines);
        // 3. Fetch all applicators
        $applicatorsMap = fetchApplicatorsByHpNos($uniqueApplicators);

        // 4. Validate
        $validated = [];
        $errors    = [];
        foreach ($rows as $line => $r) {
            $shift  = trim((string)($r['Shift'] ?? ''));
            $machineNo = trim((string)($r['Machine No'] ?? ''));
            $app1No    = trim((string)($r['Applicator1'] ?? ''));
            $app2No    = trim((string)($r['Applicator2'] ?? ''));
            $date      = trim((string)($r['Date'] ?? ''));
            $outputVal = (int)($r['Output'] ?? 0);

            $displayLine = $line + 4;

            if ($app1No === '') {
                $errors[] = "Row $displayLine: Applicator1 is required.";
                continue;
            }
            if (!isset($applicatorsMap[$app1No])) {
                $errors[] = "Row $displayLine: Applicator1 $app1No not found.";
                continue;
            }
            if ((int)$applicatorsMap[$app1No]['is_active'] !== 1) {
                $errors[] = "Row $displayLine: Applicator1 $app1No is inactive.";
                continue;
            }
            if ($app2No !== '') {
                if ($app2No === $app1No) {
                    $errors[] = "Row $displayLine: Duplicate applicator entry ($app1No).";
                    continue;
                }
                if (!isset($applicatorsMap[$app2No])) {
                    $errors[] = "Row $displayLine: Applicator2 $app2No not found.";
                    continue;
                }
                if ((int)$applicatorsMap[$app2No]['is_active'] !== 1) {
                    $errors[] = "Row $displayLine: Applicator2 $app2No is inactive.";
                    continue;
                }
            }
            if ($machineNo === '') {
                $errors[] = "Row $displayLine: Machine No is required.";
                continue;
            }
            if (!isset($machinesMap[$machineNo])) {
                $errors[] = "Row $displayLine: Machine $machineNo not found.";
                continue;
            }
            if ((int)$machinesMap[$machineNo]['is_active'] !== 1) {
                $errors[] = "Row $displayLine: Machine $machineNo is inactive.";
                continue;
            }

            if ($shift === null) {
                $errors[] = "Row $displayLine: Invalid shift '$shift'.";
                continue;
            }

            if ($date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $errors[] = "Row $displayLine: Invalid or missing Date (expect YYYY-MM-DD).";
                continue;
            }

            // All good, add to validated
            $validated[] = [
                'shift'   => $shift,
                'machine' => $machinesMap[$machineNo],
                'app1'    => $applicatorsMap[$app1No],
                'app2'    => ($app2No !== '' ? $applicatorsMap[$app2No] : null),
                'date'    => $date,
                'output'  => $outputVal
            ];
        }

        // 5. If any errors, abort before DB changes
        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $errors
            ];
        }

        if (empty($validated)) {
            return ['success' => false, 'message' => 'No valid rows to process.'];
        }

        // 6. Get current user ID
        $userId = (int)($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            throw new RuntimeException("User not authenticated.");
        }


        // 7. MAIN DB INSERT LOGIC
        $row_counter = 4;   // Refers to current row (for error display)
        foreach ($validated as $row) {
            // a. Create a record
            $record_id = createRecord($row['shift'], $row['machine'], $row['app1'],  
                                        $row['app2'], $row['date'], $userId);
                if (is_string($record_id)) {
                    throw new RuntimeException("Line $row_counter - $record_id");
                }
                if (!$record_id) throw new RuntimeException("Line $row_counter - Error occured while creating record.");

            // b.1 Submit applicator1 output
            $app1_status = submitApplicatorOutput($row['app1'], $row['output'], $record_id);
                if (is_string($app1_status)) {
                    throw new RuntimeException("Line $row_counter - $app1_status");
                }

            // b.2 Submit applicator2 output, if exists
            if (!is_null($row['app2'])) {
                $app2_status = submitApplicatorOutput($row['app2'], $row['output'], $record_id);
                    if (is_string($app2_status)) {
                        throw new RuntimeException("Line $row_counter - $app2_status");
                    }
            }

            // b.3 Submit machine output
            $machine_status = submitMachineOutput($row['machine'], $row['output'], $record_id);
                if (is_string($machine_status)) {
                    throw new RuntimeException("Line $row_counter - $machine_status");
                }


            // c.1 Update monitoring table for applicator1
            $app1_monitor_status = monitorApplicatorOutput($row['app1'], $row['output']);
                if (is_string($app1_monitor_status)) {
                    throw new RuntimeException("Line $row_counter - $app1_monitor_status");
                }

            // c.2 Update monitoring table for applicator2
            $app2_monitor_status = null;
                if ($row['app2']) {
                    $app2_monitor_status = monitorApplicatorOutput($row['app2'], $row['output']);
                    if (is_string($app2_monitor_status)) {
                        throw new RuntimeException("Line $row_counter - $app2_monitor_status");
                    }
                }

            // c.3 Update monitoring table for machine
            $machine_monitor_status = monitorMachineOutput($row['machine'], $row['output']);
                if (is_string($machine_monitor_status)) {
                    throw new RuntimeException("Line $row_counter - $machine_monitor_status");
                }

            $row_counter += 1;   // Update counter
        }

        // No errors were triggered, return success
        return [
            'success'        => true,
            'message'        => 'All outputs recorded successfully (batched).',
            'processed_rows' => count($validated),
            'errors'  => []
        ];
    } catch (Throwable $e) {
        return [
            'success' => false,
            'message' => 'Batch insert failed: ' . $e->getMessage(),
            'errors'  => []
        ];
    }
}
