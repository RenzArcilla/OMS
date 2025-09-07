<?php
/*
    Batch Loader for Production Data

    Efficiently loads transformed production data into the database, minimizing reads and inserts:
    - Batch existence checks
    - Batch inserts for records, applicator outputs, machine outputs
    - Aggregate monitoring updates
    - Single transaction for atomicity
*/

// Get custom parts.
require_once __DIR__ . '/../../models/read_custom_parts.php';
require_once __DIR__ . '/../../models/read_machines.php';
require_once __DIR__ . '/../../models/read_applicators.php';
require_once __DIR__ . '/../../models/update_monitor_machine.php';
require_once __DIR__ . '/../../models/update_monitor_applicator.php';

function batchLoadData(array $rows): array {
    /*
        Function: batchLoadData
        Purpose: Efficiently load transformed production data into the database
                using batch operations and aggregated updates for monitoring.

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
        $machineNo = trim((string)($r['Machine No'] ?? ''));
        $app1      = trim((string)($r['Applicator1'] ?? ''));
        $app2      = trim((string)($r['Applicator2'] ?? ''));

        if ($machineNo !== '') $uniqueMachines[$machineNo] = true;
        if ($app1 !== '') $uniqueApplicators[$app1] = true;
        if ($app2 !== '' && $app2 !== $app1) $uniqueApplicators[$app2] = true;
    }
    $uniqueMachines     = array_keys($uniqueMachines);
    $uniqueApplicators  = array_keys($uniqueApplicators);


    try {
        // 2. Fetch all machines
        $machinesMap = fetchMachinesByControlNos($uniqueMachines, $pdo);
        // 3. Fetch all applicators
        $applicatorsMap = fetchApplicatorsByHpNos($uniqueApplicators, $pdo);

        // 4. Load custom parts for machines and applicators once
        $customApplicatorDefs = getCustomParts('APPLICATOR');
        if (is_string($customApplicatorDefs)) {
            return ['success' => false, 'message' => $customApplicatorDefs];
        }
        $customMachineDefs = getCustomParts('MACHINE');
        if (is_string($customMachineDefs)) {
            return ['success' => false, 'message' => $customMachineDefs];
        }

        // Prepare custom parts template arrays
        $customAppTemplate    = [];
        foreach ($customApplicatorDefs as $def) $customAppTemplate[] = $def['part_name'];
        $customMachineTemplate = [];
        foreach ($customMachineDefs as $def) $customMachineTemplate[] = $def['part_name'];

        // 5. Validate
        $validated = [];
        $errors    = [];
        foreach ($rows as $line => $r) {
            $shiftRaw  = trim((string)($r['Shift'] ?? ''));
            $machineNo = trim((string)($r['Machine No'] ?? ''));
            $app1No    = trim((string)($r['Applicator1'] ?? ''));
            $app2No    = trim((string)($r['Applicator2'] ?? ''));
            $date      = trim((string)($r['Date'] ?? ''));
            $outputVal = (int)($r['Output'] ?? 0);

            if ($app1No === '') {
                $errors[] = "Row " . ($line + 4) . ": Applicator1 is required.";
                continue;
            }
            if (!isset($applicatorsMap[$app1No])) {
                $errors[] = "Row " . ($line + 4) . ": Applicator1 $app1No not found.";
                continue;
            }
            if ($app2No !== '') {
                if ($app2No === $app1No) {
                    $errors[] = "Row " . ($line + 4) . ": Duplicate applicator entry ($app1No).";
                    continue;
                }
                if (!isset($applicatorsMap[$app2No])) {
                    $errors[] = "Row " . ($line + 4) . ": Applicator2 $app2No not found.";
                    continue;
                }
            }
            if ($machineNo === '') {
                $errors[] = "Row " . ($line + 4) . ": Machine No is required.";
                continue;
            }
            if (!isset($machinesMap[$machineNo])) {
                $errors[] = "Row " . ($line + 4) . ": Machine $machineNo not found.";
                continue;
            }

            $shiftFormatted = formatShift($shiftRaw);
            if ($shiftFormatted === null) {
                $errors[] = "Row " . ($line + 4) . ": Invalid shift '$shiftRaw'.";
                continue;
            }

            if ($date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $errors[] = "Row " . ($line + 4) . ": Invalid or missing Date (expect YYYY-MM-DD).";
                continue;
            }

            // All good, add to validated
            $validated[] = [
                'shift'   => $shiftFormatted,
                'machine' => $machinesMap[$machineNo],
                'app1'    => $applicatorsMap[$app1No],
                'app2'    => ($app2No !== '' ? $applicatorsMap[$app2No] : null),
                'date'    => $date,
                'output'  => $outputVal
            ];
        }

        // 6. If any errors, abort before DB changes
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

        // Get current user ID
        $userId = (int)($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            throw new RuntimeException("User not authenticated.");
        }

        // 7. Bulk INSERT records
        $recordValues = [];
        $recordParams = [];
        foreach ($validated as $i => $row) {
            $recordValues[] = "(?,?,?,?,?, ?, CURRENT_TIMESTAMP)";
            $recordParams[] = $row['shift'];
            $recordParams[] = $row['machine']['machine_id'];
            $recordParams[] = $row['app1']['applicator_id'];
            $recordParams[] = $row['app2'] ? $row['app2']['applicator_id'] : null;
            $recordParams[] = $userId;
            $recordParams[] = $row['date'];
        }

        $sqlRecords = "
            INSERT INTO records
                (shift, machine_id, applicator1_id, applicator2_id,
                    created_by, date_inspected, date_encoded)
            VALUES " . implode(',', $recordValues);

        $stmtRecords = $pdo->prepare($sqlRecords);
        foreach ($recordParams as $idx => $val) {
            $stmtRecords->bindValue($idx + 1,
                $val,
                $val === null ? PDO::PARAM_NULL : PDO::PARAM_STR
            );
        }
        $stmtRecords->execute();

        $firstRecordId = (int)$pdo->lastInsertId();
        $recordIds     = [];
        for ($i = 0; $i < count($validated); $i++) {
            $recordIds[$i] = $firstRecordId + $i;
        }

        // 8. Prepare applicator & machine outputs
        $sideRows  = [];
        $sideParams = [];
        $endRows   = [];
        $endParams = [];

        $monitorApplicatorAgg = [];
        $monitorMachineAgg    = [];

        // Aggregate machine outputs and applicator outputs
        foreach ($validated as $idx => $row) {
            $recordId  = $recordIds[$idx];
            $outputVal = (int)$row['output'];

            $monitorMachineAgg[$row['machine']['machine_id']] =
                ($monitorMachineAgg[$row['machine']['machine_id']] ?? 0) + $outputVal;

            $monitorApplicatorAgg[$row['app1']['applicator_id']] =
                ($monitorApplicatorAgg[$row['app1']['applicator_id']] ?? 0) + $outputVal;

            if ($row['app2']) {
                $monitorApplicatorAgg[$row['app2']['applicator_id']] =
                    ($monitorApplicatorAgg[$row['app2']['applicator_id']] ?? 0) + $outputVal;
            }

            buildApplicatorOutputRow(
                $row['app1'],
                $recordId,
                $outputVal,
                $customAppTemplate,
                $sideRows,
                $sideParams,
                $endRows,
                $endParams
            );

            if ($row['app2']) {
                buildApplicatorOutputRow(
                    $row['app2'],
                    $recordId,
                    $outputVal,
                    $customAppTemplate,
                    $sideRows,
                    $sideParams,
                    $endRows,
                    $endParams
                );
            }
        }

        // 9. Prepare machine outputs
        $machineOutputValues = [];
        $machineOutputParams = [];
        foreach ($validated as $idx => $row) {
            $recordId  = $recordIds[$idx];
            $outputVal = (int)$row['output'];

            $customPartsMachine = [];
            foreach ($customMachineTemplate as $n) {
                $customPartsMachine[] = ['name' => $n, 'value' => $outputVal];
            }
            $customJson = json_encode($customPartsMachine);

            $machineOutputValues[] = "(?,?,?,?,?,?, ?)";
            $machineOutputParams[] = $recordId;
            $machineOutputParams[] = $row['machine']['machine_id'];
            $machineOutputParams[] = $outputVal;
            $machineOutputParams[] = $outputVal;
            $machineOutputParams[] = $outputVal;
            $machineOutputParams[] = $outputVal;
            $machineOutputParams[] = $customJson;
        }

        // 10. Batch insert: applicator outputs
        if ($sideRows) {
            $sqlSide = "
                INSERT INTO applicator_outputs
                        (record_id, applicator_id, total_output,
                        wire_crimper, wire_anvil, insulation_crimper, insulation_anvil,
                        slide_cutter, cutter_holder, custom_parts)
                VALUES " . implode(',', $sideRows);
            $stmtSide = $pdo->prepare($sqlSide);
            bindAllSequential($stmtSide, $sideParams);
            $stmtSide->execute();
        }
        if ($endRows) {
            $sqlEnd = "
                INSERT INTO applicator_outputs
                    (record_id, applicator_id, total_output,
                        wire_crimper, wire_anvil, insulation_crimper, insulation_anvil,
                        shear_blade, cutter_a, cutter_b, custom_parts)
                VALUES " . implode(',', $endRows);
            $stmtEnd = $pdo->prepare($sqlEnd);
            bindAllSequential($stmtEnd, $endParams);
            $stmtEnd->execute();
        }

        // 11. Batch insert: machine outputs
        if ($machineOutputValues) {
            $sqlMachine = "
                INSERT INTO machine_outputs
                        (record_id, machine_id, total_machine_output,
                        cut_blade, strip_blade_a, strip_blade_b, custom_parts)
                VALUES " . implode(',', $machineOutputValues);
            $stmtM = $pdo->prepare($sqlMachine);
            bindAllSequential($stmtM, $machineOutputParams);
            $stmtM->execute();
        }

        // 12. Monitoring updates
        applyApplicatorMonitoringAggregates($pdo, $monitorApplicatorAgg, $customAppTemplate);
        applyMachineMonitoringAggregates($pdo, $monitorMachineAgg, $customMachineTemplate);

        return [
            'success'        => true,
            'message'        => 'All outputs recorded successfully (batched).',
            'processed_rows' => count($validated)
        ];
    } catch (Throwable $e) {
        return [
            'success' => false,
            'message' => 'Batch insert failed: ' . $e->getMessage()
        ];
    }
}


function formatShift(string $shift): ?string {
    /*
        Convert various shift inputs to standardized format.
        Returns null if invalid.
    */
    $s = strtoupper($shift);
    return match ($s) {
        'FIRST' => '1st',
        'SECOND' => '2nd',
        'NIGHT' => 'NIGHT',
        default => null
    };
}


function buildApplicatorOutputRow(array $appData, int $recordId, int $outputVal,
                                array $customTemplate,
                                array &$sideRows, array &$sideParams,
                                array &$endRows, array &$endParams): void {
    /*
        Build applicator output depending on type.
        Appends to the provided arrays by reference.
        Used when recording outputs for machines or applicators in batchLoadData.
    */
    
    // Determine type
    $type = trim($appData['description']);
    $appId = (int)$appData['applicator_id'];

    // Build custom parts JSON
    $customParts = [];
    foreach ($customTemplate as $name) {
        $customParts[] = ['name' => $name, 'value' => $outputVal];
    }
    $customJson = json_encode($customParts);

    if ($type === 'SIDE') {
        // (record_id, applicator_id, total_output, wire_crimper, wire_anvil,
        //  insulation_crimper, insulation_anvil, slide_cutter, cutter_holder, custom_parts)
        $sideRows[] = "(?,?,?,?,?,?,?,?,?, ?)";
        array_push(
            $sideParams,
            $recordId, $appId, $outputVal,
            $outputVal, $outputVal,
            $outputVal, $outputVal,
            $outputVal, $outputVal,
            $customJson
        );
    } elseif (in_array($type, ['END', 'CLAMP', 'STRIP AND CRIMP'], true)) {
        // (record_id, applicator_id, total_output, wire_crimper, wire_anvil,
        //  insulation_crimper, insulation_anvil, shear_blade, cutter_a, cutter_b, custom_parts)
        $endRows[] = "(?,?,?,?,?,?,?,?,?,?, ?)";
        array_push(
            $endParams,
            $recordId, $appId, $outputVal,
            $outputVal, $outputVal,
            $outputVal, $outputVal,
            $outputVal, $outputVal, $outputVal,
            $customJson
        );
    } else {
        throw new RuntimeException("Invalid applicator type: $type");
    }
}

function bindAllSequential(PDOStatement $stmt, array $params): void {
    /*
        Bind all parameters sequentially to a prepared statement.
        Used for batch inserts with many parameters.
    */

    foreach ($params as $i => $val) {
        $type = PDO::PARAM_STR;
        if (is_int($val)) $type = PDO::PARAM_INT;
        if ($val === null) $type = PDO::PARAM_NULL;
        $stmt->bindValue($i + 1, $val, $type);
    }
}
