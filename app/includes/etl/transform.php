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
        // Normalize Production Date to Y-m-d (handles Excel serials)
        $dateRaw = $row['Production Date'] ?? '';
        if (is_numeric($dateRaw)) {
            $base = new DateTime('1899-12-30');
            $base->modify('+' . (int)$dateRaw . ' days');
            $row['Date'] = $base->format('Y-m-d');
        } else {
            $ts = strtotime((string)$dateRaw);
            $row['Date'] = $ts ? date('Y-m-d', $ts) : '';
        }

        // Clean Machine No
        $row['Machine No'] = strtoupper(trim($row['Machine No'] ?? ''));

        // Normalize Shift
        $shift = strtolower(trim($row['Shift'] ?? ''));
        if ($shift === '1' || strpos($shift, '1st') !== false || strpos($shift, 'first') !== false) {
            $row['Shift'] = 'FIRST';
        } elseif ($shift === '2' || strpos($shift, '2nd') !== false || strpos($shift, 'second') !== false) {
            $row['Shift'] = 'SECOND';
        } elseif (strpos($shift, 'night') !== false || strpos($shift, '3rd') !== false) {
            $row['Shift'] = 'NIGHT';
        } else {
            return "Invalid shift value in data: " . ($row['Shift'] ?? '');
        }

        // Cast output to int, if exists
        $row['Output'] = isset($row['Total Output Qty']) ? (int) $row['Total Output Qty'] : 0;

        // Trim applicators
        $row['Applicator 1'] = strtoupper(trim($row['Applicator 1'] ?? ''));
        $row['Applicator 1'] = $row['Applicator 1'] !== '' ? $row['Applicator 1'] : null;
        $row['Applicator 2'] = isset($row['Applicator 2']) && trim($row['Applicator 2']) !== ''
            ? strtoupper(trim($row['Applicator 2']))
            : null;
    }
    
    return $data;
}   
