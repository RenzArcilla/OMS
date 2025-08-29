<?php
/*
    This controller file is the entry point for displaying disabled records.
*/

// Include the model for fetching records
require_once '../models/read_joins/record_and_outputs.php';

// Simple validation
$search = isset($_GET['q']) ? trim($_GET['q']) : null;
if ($search === '') {
    $search = null;
}

// Fetch the records
$records = getDisabledRecordsAndOutputs(20, 0, $search);

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($records);
