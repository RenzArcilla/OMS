<?php
/*
    This PHP code handles the file upload and ETL pipeline trigger for the file upload form in file_upload.php.
*/


// Include necessary files
require_once '../includes/auth.php';
require_once __DIR__ . '/../includes/etl/extract.php';
require_once __DIR__ . '/../includes/etl/transform.php';
require_once __DIR__ . '/../includes/etl/load.php';
require_once __DIR__ . '/../includes/db.php'; 

// Require Toolkeeper/Admin Privileges
requireToolkeeper();

$tempDir = __DIR__ . '/../temp/'; // Directory where uploaded files will be temporarily stored

// Redirect url
$redirect_url = "../views/file_upload.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsAlertRedirect("Invalid request method.", $redirect_url);
    exit();
}

// Check if the temp dir is empty
if (!isset($_FILES['dataFiles'])) { 
    jsAlertRedirect("No files uploaded.", $redirect_url);
    exit();
}

$pdo->beginTransaction();

try {
    $fileCount = count($_FILES['dataFiles']['name']);
    $totalSize = array_sum($_FILES['dataFiles']['size']);

    // Only 1 file allowed
    if ($fileCount !== 1) {
        jsAlertRedirect("Error: Only one file can be uploaded at a time.", $redirect_url);
        exit();
    }

    // Delete any existing file in temp dir
    foreach (glob($tempDir . "*") as $oldFile) {
        if (is_file($oldFile)) unlink($oldFile);
    }

    $fileName   = basename($_FILES['dataFiles']['name'][0]);
    $tmpName    = $_FILES['dataFiles']['tmp_name'][0];
    $fileSize   = $_FILES['dataFiles']['size'][0];

    // Validate file extension
    $allowedExtensions = ['xls', 'xlsx'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions)) {
        jsAlertRedirect("Error: Invalid file type. Only XLS and XLSX are allowed.", $redirect_url);
        exit();
    }

    // Max file size check (10 MB)
    $maxFileSize = 10 * 1024 * 1024; 
    if ($fileSize > $maxFileSize) {
        jsAlertRedirect("Error: File '$fileName' exceeds the 10MB size limit.", $redirect_url);
        exit();
    }

    // Define target path
    $targetPath = $tempDir . uniqid() . "_" . $fileName;

    // Moves the uploaded file to the target path.
    if (move_uploaded_file($tmpName, $targetPath)) { 
        
        // Extract from file
        $rawData = extractData($targetPath); 
        if (is_string($rawData)) {
            jsAlertRedirect($rawData, $redirect_url);
            exit();
        }

        // Transform data
        $cleanData = transformData($rawData); 
        unlink($targetPath); // Deletes the file after ETL
        echo print_r($cleanData);
        exit();
        if (is_string($cleanData)) {
            jsAlertRedirect($cleanData, $redirect_url);
            exit();
        }

        // Load to db
        $result = loadData($cleanData); 
        if ($result === "All outputs recorded successfully!") {
            $pdo->commit(); // Commit transaction if all operations succeed
            unlink($targetPath); // Deletes the file after ETL
            jsAlertRedirect($result, $redirect_url); 
        } else {
            $pdo->rollBack(); // Rollback transaction in case of error
            unlink($targetPath); // Deletes the file if there was an error
            jsAlertRedirect($result, $redirect_url);
            exit();
        }
    }

} catch (Exception $e) {
    $pdo->rollBack(); // Rollback transaction in case of exception
    if (isset($targetPath) && file_exists($targetPath)) {
        unlink($targetPath); // Delete uploaded 
    }
    jsAlertRedirect("Error processing files: " . $e->getMessage() . " Trashing the uploaded file.", $redirect_url); // Redirect with error message
    exit();
}
