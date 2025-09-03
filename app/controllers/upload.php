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

try {
    $fileCount = count($_FILES['dataFiles']['name']);
    $totalSize = array_sum($_FILES['dataFiles']['size']);

    // Max file count check
    $maxFiles = 3;
    if ($fileCount > $maxFiles) {
        jsAlertRedirect("Error: You can only upload up to $maxFiles files at once.", $redirect_url);
        exit();
    }

    // Max total size check (15 MB)
    $maxTotalSize = 15 * 1024 * 1024; // 15 MB
    if ($totalSize > $maxTotalSize) {
        jsAlertRedirect("Error: Total upload size exceeds 15MB.", $redirect_url);
        exit();
    }

    foreach ($_FILES['dataFiles']['tmp_name'] as $index => $tmpName) { // Loops through all uploaded files.
        $fileName = basename($_FILES['dataFiles']['name'][$index]); // Extracts the original filename of the uploaded file.
        $targetPath = $tempDir . uniqid() . "_" . $fileName; // Constructs the full path (where to move the file) inside app/temp/ directory.
        
        //  Per-file size check 
        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        if ($_FILES['dataFiles']['size'][$index] > $maxFileSize) {
            jsAlertRedirect("Error: File '$fileName' exceeds the 5MB size limit.", $redirect_url);
            exit();
        }

        // Moves the uploaded file to the target path.
        if (move_uploaded_file($tmpName, $targetPath)) { 
            $pdo->beginTransaction();
                // Extract
                $rawData = extractData($targetPath); 
                if (is_string($rawData)) {
                    jsAlertRedirect($rawData, $redirect_url);
                    exit();
                }

                // Transform and Load
                $cleanData = transformData($rawData); 
                $result = loadData($cleanData); 
                if ($result === "All outputs recorded successfully!") {
                    $pdo->commit();
                    unlink($targetPath); // Deletes the file after ETL
                    jsAlertRedirect($result, $redirect_url); // Redirects to the etl_form.php with success message
                } else {
                    $pdo->rollBack(); // Rollback transaction in case of error
                    unlink($targetPath); // Deletes the file if there was an error
                    jsAlertRedirect($result, $redirect_url); // Redirects to the etl_form.php with error message
                    exit();
                }
        }
    }

} catch (Exception $e) {
    $pdo->rollBack(); // Rollback transaction in case of exception
    if (isset($targetPath) && file_exists($targetPath)) {
        unlink($targetPath); // Delete uploaded file if it exists
    }
    jsAlertRedirect("Error processing files: " . $e->getMessage() . " Trashing the uploaded file.", $redirect_url); // Redirect with error message
    exit();
}
