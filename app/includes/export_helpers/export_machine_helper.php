<?php
/*
    This is a helper file for exporting machine output to Excel.
*/

// Include error handling and set max memory limits and execution time
require_once __DIR__ . '/../../includes/error_handler.php';
ini_set('memory_limit', '512M');
set_time_limit(300);

require_once __DIR__ . '/../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/*
    Export records to Excel, splitting into multiple sheets if > 5000 rows.

    Params: 
    bool $include_headers - Whether to include column headers
    string $filters - SQL filter string for fetching records
*/
function exportMachineToExcel($include_headers) {

    $records = getMachinesForExport();

    if (!$records) {
        jsAlertRedirect("No records found for export.", '../views/dashboard_machine.php');
        exit;
    }

    $spreadsheet = new Spreadsheet();

    // Split into chunks of 5000
    $chunks = array_chunk($records, 5000);

    foreach ($chunks as $i => $chunk) {
        if ($i === 0) {
            $sheet = $spreadsheet->getActiveSheet();
        } else {
            $sheet = $spreadsheet->createSheet($i);
        }

        $sheet->setTitle("MachineResets_" . ($i + 1));

        $rowNum = 1;

        // Headers
        if ($include_headers && isset($chunk[0])) {
            $col = 1;
            foreach (array_keys($chunk[0]) as $header) {
                // Replace 'last_encoded' with 'date_encoded'
                if ($header === 'last_encoded') {
                    $header = 'date_encoded';
                }
                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $rowNum;
                $sheet->setCellValue($cell, ucfirst(str_replace("_", " ", $header)));
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $col++;
            }
            $rowNum++;
        }

        // Data rows
        foreach ($chunk as $record) {
            $col = 1;
            foreach ($record as $value) {
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
    header('Content-Disposition: attachment;filename="machine_data_export.xlsx"');
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
