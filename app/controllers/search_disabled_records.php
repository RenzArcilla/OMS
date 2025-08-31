<?php
/*
    This controller file is the entry point for displaying disabled records.
*/

// Include the model for fetching records
require_once '../models/read_joins/record_and_outputs.php';

// Validate and clean input
$search = isset($_GET['q']) ? trim($_GET['q']) : null;
if ($search === '') {
    $search = null;
}

// Fetch records
$records = getDisabledRecordsAndOutputs(20, 0, $search);

// Respond with JSON
header('Content-Type: application/json');
echo json_encode($records);
