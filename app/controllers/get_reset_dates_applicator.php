<?php
/*
    Helper: This file will be used to display available timestamps for applicator part output reset via AJAX.
*/


require_once '../includes/auth.php';
require_once '../models/read_applicator_reset.php';

// Require Default Privileges
requireDefault();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $part_name = $_POST['part_name'] ?? null;
    $applicator_id = $_POST['applicator_id'] ?? null;

    if (!$part_name || !$applicator_id) {
        echo json_encode([]);
        exit;
    }

    $resets = getApplicatorReset($applicator_id, $part_name);
    echo json_encode($resets);
}
