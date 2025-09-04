<?php
/*
    This helper handles data extraction from Excel/CSV files.

    - Uses PhpSpreadsheet to parse uploaded files.
    - Detects the header row (scanning first three rows).
    - Cleans up data by skipping blank rows and repeated headers.
    - Returns structured data ready for transformation and loading.
*/

require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

function extractData($filePath) {
    /*
        Helper: Extracts tabular data from an Excel/CSV file using PhpSpreadsheet.

        - Attempts to detect the header row by scanning the first three rows.
        - Skips blank rows and repeated header rows within the sheet.
        - Returns data as an array of associative arrays with headers as keys.

        Args:
        - $filePath: string, path to the uploaded Excel/CSV file.

        Returns:
        - array containing extracted rows keyed by header names.
        - string error message if row count is insufficient.
    */

    $spreadsheet = IOFactory::load($filePath);     
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    // Exit early if not enough rows for header and data
    if (count($sheet) < 10) return "Invalid row count";

    // Try to detect header row by checking rows 7-9 (index 6-8) 
    $headerRowIndex = -1;
    foreach (range(6, 8) as $i) { // Check only rows 7–9 (index 6–8)
        if (isset($sheet[$i]) && in_array('Production Date', $sheet[$i]) && in_array('Machine No', $sheet[$i])) {
            $headerRowIndex = $i;
            break;
        }
    }

    // Fallback
    if ($headerRowIndex === -1) {
        $headerRowIndex = 0; // Assume row 1 (index 0) contains headers
    }

    // Trim and set headers
    $headers = array_map('trim', $sheet[$headerRowIndex]);

    // Slice data starting from the index 9 (Excel row 10) to skip metadata and header rows
    $rows = array_slice($sheet, 9);

    $data = [];
    foreach ($rows as $row) {
        if (count(array_filter($row)) === 0) continue; // Skip blank rows

        // Combine header keys with current row values
        $combined = array_combine($headers, $row);

        // Detect repeated header rows mid-sheet (and skip)
        $isHeaderRow = true;
        foreach ($headers as $h) {
            if (trim($combined[$h]) !== $h) {
                $isHeaderRow = false;
                break;
            }
        }

        // Skip rows where Applicator1 is missing or empty
        if (empty(trim($combined['Applicator 1'] ?? ''))) continue;

        if (!$isHeaderRow) {
            $data[] = $combined;
        }
    }

    return $data;
}
