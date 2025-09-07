<?php
/*
    Helper: This file will be used to display available timestamps for machine part output reset via AJAX.
*/


require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../models/read_machine_reset.php';

// Require Default Privileges
requireDefault();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $part_name = $_POST['part_name'] ?? null;
    $machine_id = $_POST['machine_id'] ?? null;

    if (!$part_name || !$machine_id) {
        echo json_encode([]);
        exit;
    }

    $resets = getMachineReset($machine_id, $part_name);
    echo json_encode($resets);
}
