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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dataFiles'])) { // If the dataFiles field exists in the $_FILES array (means user uploaded one or more files).
    try {
        foreach ($_FILES['dataFiles']['tmp_name'] as $index => $tmpName) { // Loops through all uploaded files.
            $fileName = basename($_FILES['dataFiles']['name'][$index]); // Extracts the original filename of the uploaded file.
            $targetPath = $tempDir . $fileName; // Constructs the full path (where to move the file) inside app/temp/ directory.

            if (move_uploaded_file($tmpName, $targetPath)) { // Moves the uploaded file to the target path.
                $pdo->beginTransaction();
                    $rawData = extractData($targetPath); // Calls extractData() function from includes/extract.php.
                    if (is_string($rawData)) {
                        jsAlertRedirect($rawData, $redirect_url);
                        exit();
                    }
                    $cleanData = transformData($rawData); // Calls transformData() function from includes/transform.php.
                    $result = loadData($cleanData); // Passes the cleaned data to loadData() from includes/load.php.
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
}
