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
    $sheet = $spreadsheet->getActiveSheet('Sheet1')->toArray();

    // Exit early if not enough rows for header and data
    if (count($sheet) < 4) return "Invalid row count";

    // Try to detect header row by checking rows 1-3 (index 0-2)
    $headerRowIndex = -1;
    foreach (range(0, 2) as $i) { // Check only rows 1–3 (index 0–2)
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

    // Define only the required columns
    $requiredCols = [
        'Production Date',
        'Machine No',
        'Shift',
        'Total Output Qty',
        'Applicator 1',
        'Applicator 2'
    ];

    // Slice data starting from the index 3 (Excel row 4)
    $rows = array_slice($sheet, 3   );

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

        if (!$isHeaderRow) {
            $data[] = $combined;
        }
    }

    return $data;
}
