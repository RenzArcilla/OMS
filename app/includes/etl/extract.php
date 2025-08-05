<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

function extractData($filePath) {
    $spreadsheet = IOFactory::load($filePath);     
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    // Expecting real data to start from row 4, and headers at row 2 (index 1)
    if (count($sheet) < 4) return []; // Not enough rows

    $headers = array_map('trim', $sheet[1]); // Use only row 2 (index 1) as headers
    $rows = array_slice($sheet, 3); // Skip to row 4 (index 3)

    $data = [];
    foreach ($rows as $row) {
        if (count(array_filter($row)) === 0) continue; // Skip entirely blank rows

        $combined = array_combine($headers, $row);

        // Skip rows that repeat the header
        $isHeaderRow = true;
        foreach ($headers as $h) {
            if (trim($combined[$h]) !== $h) {
                $isHeaderRow = false;
                break;
            }
        }

        // Skips rows that are identical to the header row
        if (!$isHeaderRow) {
            $data[] = $combined;
        }
    }

    return $data;
}
