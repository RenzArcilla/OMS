<?php
/*
    Helper: This file provides a function to transform raw production
            data into a normalized format. It standardizes dates, machine
            numbers, shift values, output quantities, and applicator names
            for consistent storage and processing.
*/

function transformData($data) {
    /*
        Function: transformData
        Purpose: Normalize and clean raw production data for consistent storage
                and later processing. Handles date formatting, machine number
                cleaning, shift normalization, output casting, and applicator
                formatting.

        Parameters:
            - data (array): Raw production data rows to be transformed.

        Returns:
            - array: Transformed production data with standardized fields.
    */

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
        } elseif (strpos($shift, "6pm to 3am") !== false) {
            $row['Shift'] = 'NIGHT';
        } else {
            return "Invalid shift value in data: " . ($row['Shift'] ?? ''); // Return error if shift is invalid
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
