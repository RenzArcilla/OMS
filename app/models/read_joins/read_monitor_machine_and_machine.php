<?php
/*
    This file defines functions for querying and analyzing machine outputs from the database. 
    It supports:

    - Fetching machine records with cumulative outputs (with pagination & sorting).
    - Identifying the part with the highest average output.
    - Listing all parts ordered by average output (standard & custom).
    - Searching machines by control number.

    Used in the Machine Dashboard for displaying machine performance 
    and monitoring part replacement priorities.
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 

function getMachineRecordsAndOutputs(int $limit = 10, int $offset = 0, $part_names_array): array {
    /*
        Function to fetch a list of cumulative machine outputs from the database with pagination.
        It prepares and executes a SELECT query that fetches machines ordered by highest output,
        and returns them as an associative array.

        Args:
        - $limit: Maximum number of rows to fetch (default is 10).
        - $offset: Number of rows to skip (default is 0), used for pagination.

        Returns:
        - Array of machines (associative arrays) on success.
    */

    global $pdo;

    // Define allowed filter columns for security (excluding total_output)
    $standard_filters = [
        'control_no', 'last_updated', 
        'total_machine_output', 'cut_blade_output', 
        'strip_blade_a_output', 'strip_blade_b_output'
    ];
    
    // Define which columns should be sorted in ascending order
    $ascending_columns = ['machine_id', 'control_no'];
    
    // Get filter parameter
    $filter_by = $_GET['filter_by'] ?? null;
    
    // If no filter is specified, automatically find the part with highest average output
    if (!$filter_by) {
        $filter_by = findHighestOutputMachinePart($part_names_array);
    }
    
    // Check if it's a standard column filter
    if (in_array($filter_by, $standard_filters, true)) {
        $sort_order = in_array($filter_by, $ascending_columns) ? 'ASC' : 'DESC';
        $order_by_clause = $filter_by . " " . $sort_order;
    } 
    // Check if it's a custom part filter
    elseif (!empty($part_names_array) && in_array($filter_by, $part_names_array, true)) {
        // For MySQL, use JSON_EXTRACT to order by custom part values
        $order_by_clause = "CAST(JSON_UNQUOTE(JSON_EXTRACT(custom_parts_output, '$.\"{$filter_by}\"')) AS UNSIGNED) DESC";
    } 
    else {
        // Default fallback - use the first standard filter (excluding total_output)
        $filter_by = $standard_filters[0]; // cut_blade_output
        $order_by_clause = $filter_by . " DESC";
    }

    // Build the SQL query
    $sql = "
        SELECT *
        FROM monitor_machine
        LEFT JOIN machines
            USING (machine_id)
        ORDER BY " . $order_by_clause . "
        LIMIT :limit OFFSET :offset
    ";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Bind only the pagination parameters
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process custom parts output
    foreach ($records as &$row) {
        if (!empty($row['custom_parts_output'])) {
            // Check if it's already an array or if it's a JSON string that needs decoding
            if (is_string($row['custom_parts_output'])) {
                // It's a JSON string, decode it
                $decoded = json_decode($row['custom_parts_output'], true);
                $row['custom_parts_output'] = is_array($decoded) ? $decoded : [];
            } elseif (is_array($row['custom_parts_output'])) {
                // It's already an array, use it as is
                $row['custom_parts_output'] = $row['custom_parts_output'];
            } else {
                // Neither string nor array, set to empty array
                $row['custom_parts_output'] = [];
            }
        } else {
            $row['custom_parts_output'] = [];
        }
    }

    return $records;
}

function findHighestOutputMachinePart($part_names_array): string {
    /*
        Function to find the part with the highest average output across all machines.
        This helps identify which parts are most likely to need replacement.
        
        Returns:
        - String: The column name of the part with the highest average output
    */
    
    global $pdo;
    
    // Standard parts to check (excluding total_output)
    $parts_to_check = [
        'cut_blade_output', 'strip_blade_a_output',
        'strip_blade_b_output'
    ];
    $highest_avg = 0;
    $highest_part = 'cut_blade_output'; // default fallback
    
    // Check standard parts
    foreach ($parts_to_check as $part) {
        $sql = "SELECT AVG($part) as avg_output FROM monitor_machine WHERE $part > 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $avg_output = floatval($result['avg_output']);
        if ($avg_output > $highest_avg) {
            $highest_avg = $avg_output;
            $highest_part = $part;
        }
    }
    
    // Check custom parts if they exist
    if (!empty($part_names_array)) {
        foreach ($part_names_array as $custom_part) {
            $sql = "
                SELECT AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(custom_parts_output, '$.\"{$custom_part}\"')) AS UNSIGNED)) as avg_output 
                FROM monitor_machine 
                WHERE JSON_EXTRACT(custom_parts_output, '$.\"{$custom_part}\"') IS NOT NULL
                AND CAST(JSON_UNQUOTE(JSON_EXTRACT(custom_parts_output, '$.\"{$custom_part}\"')) AS UNSIGNED) > 0
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $avg_output = floatval($result['avg_output']);
            if ($avg_output > $highest_avg) {
                $highest_avg = $avg_output;
                $highest_part = $custom_part;
            }
        }
    }
    
    return $highest_part;
}

function getPartsOrderedByMachineOutput($part_names_array): array {
    /*
        Function to get all parts ordered by their average output.
        Useful for dashboard analytics and identifying replacement priorities.
        
        Returns:
        - Array: Parts ordered by average output (highest first)
    */
    
    global $pdo;
    
    $parts_data = [];

    // Standard parts to check
    $standard_parts = [
        'cut_blade_output' => 'Cut Blade',
        'strip_blade_a_output' => 'Strip Blade A',
        'strip_blade_b_output' => 'Strip Blade B',
    ];
    
    // Get averages for standard parts
    foreach ($standard_parts as $column => $display_name) {
        $sql = "SELECT AVG($column) as avg_output, MAX($column) as max_output FROM monitor_machine WHERE $column > 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $parts_data[] = [
            'column_name' => $column,
            'display_name' => $display_name,
            'avg_output' => floatval($result['avg_output']),
            'max_output' => floatval($result['max_output']),
            'type' => 'standard'
        ];
    }
    
    // Get averages for custom parts
    if (!empty($part_names_array)) {
        foreach ($part_names_array as $custom_part) {
            $sql = "
                SELECT 
                    AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(custom_parts_output, '$.\"{$custom_part}\"')) AS UNSIGNED)) as avg_output,
                    MAX(CAST(JSON_UNQUOTE(JSON_EXTRACT(custom_parts_output, '$.\"{$custom_part}\"')) AS UNSIGNED)) as max_output
                FROM monitor_machine 
                WHERE JSON_EXTRACT(custom_parts_output, '$.\"{$custom_part}\"') IS NOT NULL
                AND CAST(JSON_UNQUOTE(JSON_EXTRACT(custom_parts_output, '$.\"{$custom_part}\"')) AS UNSIGNED) > 0
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $parts_data[] = [
                'column_name' => $custom_part,
                'display_name' => $custom_part,
                'avg_output' => floatval($result['avg_output']),
                'max_output' => floatval($result['max_output']),
                'type' => 'custom'
            ];
        }
    }
    
    // Sort by average output (highest first)
    usort($parts_data, function($a, $b) {
        return $b['avg_output'] <=> $a['avg_output'];
    });
    
    return $parts_data;
}

function searchMachineByControlNo($control_no, $part_names_array): array {
    /*
        Function to search for machines by (partial) control number.
        
        Args:
        - $control_no: The control number (partial) to search for
        - $part_names_array: Array of custom part names
        
        Returns:
        - Array of machine records (can be empty if no match)
    */
    
    global $pdo;
    
    $sql = "
        SELECT *
        FROM monitor_machine
        LEFT JOIN machines
            USING (machine_id)
        WHERE LOWER(control_no) LIKE LOWER(:control_no)
    ";
    
    $stmt = $pdo->prepare($sql);
    // Add wildcards for partial matching
    $stmt->bindValue(':control_no', '%' . trim(strtolower($control_no)) . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Normalize custom_parts_output for each record
    foreach ($records as &$record) {
        if (!empty($record['custom_parts_output'])) {
            if (is_string($record['custom_parts_output'])) {
                $decoded = json_decode($record['custom_parts_output'], true);
                $record['custom_parts_output'] = is_array($decoded) ? $decoded : [];
            } elseif (is_array($record['custom_parts_output'])) {
                $record['custom_parts_output'] = $record['custom_parts_output'];
            } else {
                $record['custom_parts_output'] = [];
            }
        } else {
            $record['custom_parts_output'] = [];
        }
    }
    
    return $records;
}


function getMachineOutputsForExport() {
    /*
        Model function to fetch Machine Output records.
        This function will be used for exporting Machine Output to Excel.
    */

    global $pdo;

    // Build the SQL query
    $sql = "
        SELECT m.machine_id,
                mm.last_updated,
                m.control_no,
                m.description,
                m.model,
                m.maker,
                m.serial_no,
                m.invoice_no,
                mm.total_machine_output,
                mm.cut_blade_output,
                mm.strip_blade_a_output,
                mm.strip_blade_b_output,
                mm.custom_parts_output
        FROM monitor_machine as mm
        LEFT JOIN machines as m
            USING (machine_id)
        ORDER BY m.machine_id
    ";
    
    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Execute the query and return the results
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the custom part names of machines
    require_once __DIR__ . '/../models/read_custom_parts.php';
    $custom_parts = getCustomParts("MACHINE");

    // Process custom parts output
    foreach ($records as &$row) {
        if (!empty($row['custom_parts_output'])) {
            // Check if it's already an array or if it's a JSON string that needs decoding
            if (is_string($row['custom_parts_output'])) {
                // It's a JSON string, decode it
                $decoded = json_decode($row['custom_parts_output'], true);
                $row['custom_parts_output'] = is_array($decoded) ? $decoded : [];
            } elseif (is_array($row['custom_parts_output'])) {
                // It's already an array, use it as is
                $row['custom_parts_output'] = $row['custom_parts_output'];
            } else {
                // Neither string nor array, set to empty array
                $row['custom_parts_output'] = [];
            }
        } else {
            $row['custom_parts_output'] = [];
        }
    }

    return $records;
}


function getMachineRecordsCount($part_names_array): int {
    /*
        Function to get the total count of applicator records for pagination.
        
        Args:
        - $part_names_array: Array of custom part names.
        
        Returns:
        - int: Total number of machine records.
    */
    
    global $pdo;
    
    // Simple count query without ordering or filtering
    $sql = "
        SELECT COUNT(*) as total
        FROM monitor_machine
        LEFT JOIN machines
            USING (machine_id)
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return (int)$result['total'];
}