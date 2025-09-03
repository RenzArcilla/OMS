<?php
/*
    This is a helper file for exporting applicator output to Excel.
*/

// Include error handling and set max memory limits and execution time
require_once '../includes/error_handler.php';
ini_set('memory_limit', '512M');
set_time_limit(300);

require_once '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/*
    Export records to Excel, splitting into multiple sheets if > 5000 rows.

    Params: 
    bool $include_headers - Whether to include column headers
    string $filters - SQL filter string for fetching records
*/
function exportApplicatorOutputToExcel($include_headers) {

    $records = getApplicatorOutputsForExport();

    if (!$records) {
        jsAlertRedirect("No records found for export.", '../views/dashboard_applicator.php');
    }

    // Extract only the part_name values from custom applicator parts 
    // to use as dynamic column headers in the export
    $custom_parts = array_map(function($row) {
        return $row['part_name'];
    }, getCustomParts("APPLICATOR"));

    $spreadsheet = new Spreadsheet();

    // Split into chunks of 5000
    $chunks = array_chunk($records, 5000);

    foreach ($chunks as $i => $chunk) {
        if ($i === 0) {
            $sheet = $spreadsheet->getActiveSheet();
        } else {
            $sheet = $spreadsheet->createSheet($i);
        }

        $sheet->setTitle("Applicator_Output_" . ($i + 1));

        $rowNum = 1;

        // Headers
        if ($include_headers && isset($chunk[0])) {
            $col = 1;
            foreach ($chunk[0] as $header => $value) {
                if ($header === 'custom_parts_output') {
                    continue; // skip placeholder column
                }
                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $rowNum;
                $sheet->setCellValue($cell, ucfirst(str_replace("_", " ", $header)));
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $col++;
            }

            // Add headers for each custom part
            foreach ($custom_parts as $part_name) {
                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $rowNum;
                $sheet->setCellValue($cell, ucfirst(str_replace("_", " ", $part_name)));
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $col++;
            }

            $rowNum++;
        }

        // Data rows
        foreach ($chunk as $record) {
            $col = 1;

            // Write normal fields first
            foreach ($record as $key => $value) {
                if ($key === 'custom_parts_output') {
                    continue; // Skip this field, we’ll expand it later
                }

                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $rowNum;
                $sheet->setCellValue($cell, $value);
                $col++;
            }

            // Expand custom parts into their own columns
            $custom_output = $record['custom_parts_output'] ?? [];
            foreach ($custom_parts as $part_name) {
                $value = $custom_output[$part_name] ?? 0;
                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $rowNum;
                $sheet->setCellValue($cell, $value);
                $col++;
            }

            $rowNum++;
        }

        // Auto-size columns
        foreach (range(1, count($chunk[0])) as $colIndex) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }

    $spreadsheet->setActiveSheetIndex(0);

    // Clear any previous output
    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="applicator_output_export.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1'); // For IE compatibility
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
