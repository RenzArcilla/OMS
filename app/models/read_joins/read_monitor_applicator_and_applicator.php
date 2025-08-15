<?php
/*
    This file defines a function that queries a list of cumulative applicator outputs from the database.
    Used in the applicator dashboard table.
*/

// Include the database connection
require_once __DIR__ . '/../../includes/db.php'; 

function getRecordsAndOutputs(int $limit = 10, int $offset = 0, $part_names_array): array {
    /*
    Function to fetch a list of cumulative applicator outputs from the database with pagination.
    It prepares and executes a SELECT query that fetches applicators ordered by highest output,
    and returns them as an associative array.

    Args:
    - $limit: Maximum number of rows to fetch (default is 10).
    - $offset: Number of rows to skip (default is 0), used for pagination.

    Returns:
    - Array of machines (associative arrays) on success.
    */

    global $pdo;

    // Define allowed filter columns for security
    $allowed_filters = [
        'hp_no', 'last_updated', 'total_output', 'wire_crimper_output', 'wire_anvil_output',
        'insulation_crimper_output', 'insulation_anvil_output', 'slide_cutter_output', 
        'cutter_holder_output', 'shear_blade_output', 'cutter_a_output', 'cutter_b_output'
    ]; 
    
    // Get filter parameter and validate it
    $filter_by = (isset($_GET['filter_by']) && in_array($_GET['filter_by'], $allowed_filters, true))
        ? $_GET['filter_by']
        : 'total_output';

    // Build the SQL query with the validated column name directly in the string
    // This is safe because we've validated the column name against an allowlist
    $sql = "
        SELECT *
        FROM monitor_applicator
        LEFT JOIN applicators
            USING (applicator_id)
        ORDER BY " . $filter_by . " DESC
        LIMIT :limit OFFSET :offset
    ";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Bind only the pagination parameters (not the column name)
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

    // Update part names array if we have records
    if (!empty($records[0]['custom_parts_output'])) {
        $part_names_array = array_keys($records[0]['custom_parts_output']);
    }

    return $records;
}