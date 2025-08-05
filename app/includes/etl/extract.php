<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

function extractData($filePath) {
    $spreadsheet = IOFactory::load($filePath);     
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    // Exit early if not enough rows for header and data
    if (count($sheet) < 4) return "Invalid row count";

    // Try to detect header row by checking rows 1–3 (index 0–2)
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

    // Slice data starting from the index 3 (Excel row 4)
    $rows = array_slice($sheet, 3); 

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
