<?php
/*
    This PHP code handles the file upload and ETL pipeline trigger for the file upload form in file_upload.php.
*/


// Include necessary files
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/js_alert.php';
require_once __DIR__ . '/../includes/etl/load.php';
require_once __DIR__ . '/../includes/etl/extract.php';
require_once __DIR__ . '/../includes/etl/transform.php';

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

$tempDir = __DIR__ . '/../temp/';
$redirect_url = "../views/record_output.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit;
}

// Check if files were uploaded
if (!isset($_FILES['dataFiles'])) {
    jsAlertRedirect("No files uploaded.", $redirect_url);
    exit;
}

// Check if the uploaded file has a name (not empty)
if (empty($_FILES['dataFiles']['name'][0])) {
    jsAlertRedirect("No file selected. Please choose a file to upload.", $redirect_url);
    exit;
}

try {
    // Allow only one file at a time
    $fileCount = count($_FILES['dataFiles']['name']);
    if ($fileCount !== 1) {
        jsAlertRedirect("Error: Only one file can be uploaded at a time.", $redirect_url);
        exit;
    }

    // Clear old temp files
    foreach (glob($tempDir . "*") as $oldFile) {
        if (is_file($oldFile)) unlink($oldFile);
    }

    $fileName = basename($_FILES['dataFiles']['name'][0]);
    $tmpName  = $_FILES['dataFiles']['tmp_name'][0];
    $fileSize = $_FILES['dataFiles']['size'][0];

    // Validate file extension
    $allowedExtensions = ['xls', 'xlsx'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions)) {
        jsAlertRedirect("Error: Invalid file type. Only XLS and XLSX are allowed.", $redirect_url);
        exit;
    }

    // Limit file size to 10MB
    $maxFileSize = 10 * 1024 * 1024;
    if ($fileSize > $maxFileSize) {
        jsAlertRedirect("Error: File '$fileName' exceeds size limit.", $redirect_url);
        exit;
    }

    // Move uploaded file to a temporary directory
    $targetPath = $tempDir . uniqid() . "_" . $fileName;
    if (!move_uploaded_file($tmpName, $targetPath)) {
        jsAlertRedirect("Failed to move uploaded file.", $redirect_url);
        exit;
    }

    // ETL Pipeline
    // 1. Extract
    $rawData = extractData($targetPath);
    if (is_string($rawData)) {
        unlink($targetPath);
        jsAlertRedirect($rawData, $redirect_url);
        exit;
    }

    // 2. Transform
    $cleanData = transformData($rawData);
    if (is_string($cleanData)) {
        unlink($targetPath);
        jsAlertRedirect($cleanData, $redirect_url);
        exit;
    }

    // 3. Load
    global $pdo;
    $pdo->beginTransaction();
    $result = LoadData($cleanData); 

    if (!$result['success']) {
        $pdo->rollBack();
        unlink($targetPath);

        // Build error message for display
        $msg = $result['message'];
        if (!empty($result['errors'])) {
            // Show only the first 5 errors (separated by '\n') to not overwhelm users
            $extras = implode("\n", array_slice($result['errors'], 0, 5));
            $msg .= "\nErrors:\n" . $extras;
        }

        // Prepare error log data
        $errorsToLog = isset($result['errors']) && is_array($result['errors'])
            ? array_slice($result['errors'], 0, 5)
            : [];

        // Log and display errors
        error_log("LoadData failed: {$result['message']} | Errors: " . implode(" | ", $errorsToLog));
        jsAlertRedirect($msg, $redirect_url);
        exit;
    }

    $pdo->commit();
    unlink($targetPath);
    jsAlertRedirect($result['message'], $redirect_url);
    exit;

} catch (Throwable $e) {
    error_log("OMS batch load error: " . $e->getMessage()); // Log error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    if (isset($targetPath) && file_exists($targetPath)) unlink($targetPath);
    jsAlertRedirect("Error processing file: " . $e->getMessage(), $redirect_url);
    exit;
}