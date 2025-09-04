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

    // Exit if the sheet is too short
    if (count($sheet) < 10) return "Invalid row count";

    // Build headers from merged header rows 7-9 (indexes 6..8)
    $headerRows = [];
    for ($r = 6; $r <= 8; $r++) {
        $headerRows[$r] = isset($sheet[$r]) ? $sheet[$r] : [];
    }
    $headers = [];
    $maxCols = 0;
    foreach ($headerRows as $row) {
        $maxCols = max($maxCols, is_array($row) ? count($row) : 0);
    }
    for ($c = 0; $c < $maxCols; $c++) {
        $parts = [];
        for ($r = 6; $r <= 8; $r++) {
            $val = isset($headerRows[$r][$c]) ? trim((string)$headerRows[$r][$c]) : '';
            if ($val !== '' && !in_array($val, $parts, true)) {
                $parts[] = $val;
            }
        }
        $headers[$c] = trim(implode(' ', $parts));
    }

    // Columns we care about
    $requiredCols = [
        'Production Date',
        'Machine No',
        'Shift',
        'Total Output Qty',
        'Applicator 1',
        'Applicator 2'
    ];

    // Helper to normalize strings (remove special spaces/punct, lowercase)
    $normalize = function($str) {
        $s = preg_replace('/\s+/u', ' ', (string)$str);
        $s = trim($s);
        // Replace non-breaking spaces
        $s = str_replace("\xc2\xa0", ' ', $s);
        // Remove punctuation
        $s = preg_replace('/[^a-z0-9 ]/i', '', $s);
        // Collapse spaces and remove them for substring compare
        $s = strtolower(str_replace(' ', '', $s));
        return $s;
    };

    // Build normalized headers once
    $normalizedHeaders = [];
    foreach ($headers as $i => $hdr) {
        $normalizedHeaders[$i] = $normalize($hdr);
    }

    // Allow synonyms for some columns
    $synonyms = [
        'Total Output Qty' => ['Total Output Qty', 'Total Output', 'Output Qty', 'TotalQty', 'Qty']
    ];

    // Map required header names to their column indexes (allow merged/concatenated headers)
    $colIndexes = [];
    foreach ($requiredCols as $col) {
        $candidates = $synonyms[$col] ?? [$col];
        $found = false;
        foreach ($candidates as $cand) {
            $needle = $normalize($cand);
            foreach ($normalizedHeaders as $i => $nh) {
                if ($needle !== '' && strpos($nh, $needle) !== false) {
                    $colIndexes[$col] = $i;
                    $found = true;
                    break 2;
                }
            }
        }
        if (!$found) {
            // Log available headers to assist troubleshooting
            error_log('[ETL extract] Available headers: ' . json_encode($headers));
            return "Missing required column in header: $col";
        }
    }

    $data = [];

    // Data starts at row 10 (index 9)
    for ($i = 9; $i < count($sheet); $i++) {
        $row = $sheet[$i];

        // Skip blank rows
        if (count(array_filter($row)) === 0) continue;

        // Build associative array with only required columns
        $entry = [];
        foreach ($colIndexes as $col => $idx) {
            $entry[$col] = $row[$idx] ?? null;
        }

        // Skip rows with missing Applicator 1
        if (empty(trim($entry['Applicator 1'] ?? ''))) continue;

        $data[] = $entry;
    }

    return $data;
}