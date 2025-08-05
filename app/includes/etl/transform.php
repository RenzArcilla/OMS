<?php
function transformData($data) {
    foreach ($data as &$row) {
        // Normalize Production Date to Y-m-d
        $row['Date'] = date('Y-m-d', strtotime($row['Production Date'] ?? ''));

        // Clean Machine No
        $row['Machine No'] = strtoupper(trim($row['Machine No'] ?? ''));

        // Normalize Shift
        $shift = strtolower(trim($row['Shift'] ?? ''));
        if (strpos($shift, "6am to 2pm") !== false) {
            $row['Shift'] = 'FIRST';
        } elseif (strpos($shift, "2pm to 10pm") !== false) {
            $row['Shift'] = 'SECOND';
        } elseif (strpos($shift, "10pm to 6am") !== false) {
            $row['Shift'] = 'NIGHT';
        } else {
            $row['Shift'] = null;
        }

        // Cast output to int, if exists
        $row['Output'] = isset($row['Total Output Qty']) ? (int) $row['Total Output Qty'] : 0;

        // Trim applicators
        $row['Applicator1'] = strtoupper(trim($row['Applicator1'] ?? ' '));
        // Set to uppercase if not empty; otherwise, set to null
        $row['Applicator2'] = isset($row['Applicator2']) && trim($row['Applicator2']) !== ' '
            ? strtoupper(trim($row['Applicator2']))
            : null;
        }
    return $data;
}   
