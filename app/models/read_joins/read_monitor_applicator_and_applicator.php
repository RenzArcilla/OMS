<?php
/*
    This file contains functions for querying and processing applicator output records 
    from the database. It supports:

    - Fetching applicator records with pagination and sorting (standard & custom parts).
    - Finding parts with the highest average output.
    - Listing parts ordered by output for analytics.
    - Searching applicators by HP number.
    
    Used primarily in the Applicator Dashboard for displaying and analyzing 
    applicator usage and part output data.
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 

function getApplicatorRecordsAndOutputs(int $limit = 10, int $offset = 0, $part_names_array): array {
    /*
        Function to fetch a list of cumulative applicator outputs from the database with pagination.
        It prepares and executes a SELECT query that fetches applicators ordered by highest output,
        and returns them as an associative array.

        Args:
        - $limit: Maximum number of rows to fetch (default is 10).
        - $offset: Number of rows to skip (default is 0), used for pagination.

        Returns:
        - Array of applicators (associative arrays) on success.
    */

    global $pdo;

    // Define allowed filter columns for security (excluding total_output)
    $standard_filters = [
        'hp_no', 'last_updated', 'wire_crimper_output', 'wire_anvil_output',
        'insulation_crimper_output', 'insulation_anvil_output', 'slide_cutter_output', 
        'cutter_holder_output', 'shear_blade_output', 'cutter_a_output', 'cutter_b_output'
    ];
    
    // Define which columns should be sorted in ascending order
    $ascending_columns = ['hp_no'];
    
    // Get filter parameter
    $filter_by = $_GET['filter_by'] ?? null;
    
    // If no filter is specified, automatically find the part with highest average output
    if (!$filter_by) {
        $filter_by = findHighestOutputApplicatorPart($part_names_array);
    }
    
    $is_custom_part_filter = false;
    
    // Check if it's a standard column filter
    if (in_array($filter_by, $standard_filters, true)) {
        $sort_order = in_array($filter_by, $ascending_columns) ? 'ASC' : 'DESC';
        $order_by_clause = $filter_by . " " . $sort_order;
    } 
    // Check if it's a custom part filter
    elseif (!empty($part_names_array) && in_array($filter_by, $part_names_array, true)) {
        $is_custom_part_filter = true;
        // For MySQL, use JSON_EXTRACT to order by custom part values
        $order_by_clause = "CAST(JSON_UNQUOTE(JSON_EXTRACT(custom_parts_output, '$.\"{$filter_by}\"')) AS UNSIGNED) DESC";
    } 
    else {
        // Default fallback - use the first standard filter (excluding total_output)
        $filter_by = $standard_filters[2]; // wire_crimper_output
        $order_by_clause = $filter_by . " DESC";
    }

    // Build the SQL query
    $sql = "
        SELECT *
        FROM monitor_applicator
        LEFT JOIN applicators
            USING (applicator_id)
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

function findHighestOutputApplicatorPart($part_names_array): string {
    /*
        Function to find the part with the highest average output across all applicators.
        This helps identify which parts are most likely to need replacement.
        
        Returns:
        - String: The column name of the part with the highest average output
    */
    
    global $pdo;
    
    // Standard parts to check (excluding total_output)
    $parts_to_check = [
        'wire_crimper_output', 'wire_anvil_output', 'insulation_crimper_output', 
        'insulation_anvil_output', 'slide_cutter_output', 'cutter_holder_output', 
        'shear_blade_output', 'cutter_a_output', 'cutter_b_output'
    ];
    
    $highest_avg = 0;
    $highest_part = 'wire_crimper_output'; // default fallback
    
    // Check standard parts
    foreach ($parts_to_check as $part) {
        $sql = "SELECT AVG($part) as avg_output FROM monitor_applicator WHERE $part > 0";
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
                FROM monitor_applicator 
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

function getPartsOrderedByApplicatorOutput($part_names_array): array {
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
        'wire_crimper_output' => 'Wire Crimper',
        'wire_anvil_output' => 'Wire Anvil',
        'insulation_crimper_output' => 'Insulation Crimper',
        'insulation_anvil_output' => 'Insulation Anvil',
        'slide_cutter_output' => 'Slide Cutter',
        'cutter_holder_output' => 'Cutter Holder',
        'shear_blade_output' => 'Shear Blade',
        'cutter_a_output' => 'Cutter A',
        'cutter_b_output' => 'Cutter B'
    ];
    
    // Get averages for standard parts
    foreach ($standard_parts as $column => $display_name) {
        $sql = "SELECT AVG($column) as avg_output, MAX($column) as max_output FROM monitor_applicator WHERE $column > 0";
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
                FROM monitor_applicator 
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

function searchApplicatorByHpNo($hp_no, $part_names_array): array {
    /*
        Function to search for applicators by HP number.

        Args:
        - $hp_no: The HP number to search for
        - $part_names_array: Array of custom part names

        Returns:
        - Array of applicator records (empty array if none found)
    */
    
    global $pdo;

    $sql = "
        SELECT *
        FROM monitor_applicator
        LEFT JOIN applicators
            USING (applicator_id)
        WHERE LOWER(hp_no) LIKE LOWER(:hp_no)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':hp_no', '%' . trim($hp_no) . '%', PDO::PARAM_STR);
    $stmt->execute();

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($records as &$record) {
        if (!empty($record['custom_parts_output'])) {
            if (is_string($record['custom_parts_output'])) {
                $decoded = json_decode($record['custom_parts_output'], true);
                $record['custom_parts_output'] = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($record['custom_parts_output'])) {
                $record['custom_parts_output'] = [];
            }
        } else {
            $record['custom_parts_output'] = [];
        }
    }

    return $records;
}

function getApplicatorRecordsCount($part_names_array): int {
    /*
        Function to get the total count of applicator records for pagination.
        
        Args:
        - $part_names_array: Array of custom part names.
        
        Returns:
        - int: Total number of applicator records.
    */
    
    global $pdo;
    
    // Simple count query without ordering or filtering
    $sql = "
        SELECT COUNT(*) as total
        FROM monitor_applicator
        LEFT JOIN applicators
            USING (applicator_id)
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return (int)$result['total'];
}


function getApplicatorOutputsForExport() {
    /*
        Model function to fetch Applicator Output records.
        This function will be used for exporting Applicator Output to Excel.
    */

    global $pdo;

    // Build the SQL query
    $sql = "
        SELECT a.applicator_id,
                ma.last_updated,
                a.hp_no,
                a.terminal_no,
                a.description,
                a.wire,
                a.terminal_maker,
                a.applicator_maker,
                a.serial_no,
                a.invoice_no,
                ma.total_output,
                ma.wire_crimper_output,
                ma.wire_anvil_output,
                ma.insulation_crimper_output,
                ma.insulation_anvil_output,
                ma.slide_cutter_output,
                ma.cutter_holder_output,
                ma.shear_blade_output,
                ma.cutter_a_output,
                ma.cutter_b_output,
                ma.custom_parts_output
        FROM monitor_applicator as ma
        LEFT JOIN applicators as a
            USING (applicator_id)
        ORDER BY a.applicator_id
    ";
    
    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Execute the query and return the results
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the custom part names of applicators
    require_once '../models/read_custom_parts.php';
    $custom_parts = getCustomParts("APPLICATOR");

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