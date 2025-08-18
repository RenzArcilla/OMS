<?php
/*
    This file is part of reset operation for applicator parts.
*/


session_start();

require_once '../includes/js_alert.php';
require_once '../includes/db.php';
require_once '../models/create_applicator_reset.php';
require_once '../models/update_monitor_applicator.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $part_name = isset($_POST['part_name']) ? trim($_POST['part_name']) : null;
    $applicator_id = isset($_POST['applicator_id']) ? trim($_POST['applicator_id']) : null;
    print_r($_POST);

    // Get other needed arguments
    $user_id = $_SESSION['user_id'];

    // Check if fields are empty
    createApplicatorReset($user_id, $applicator_id, $part_reset, $previous_value)
    resetApplicatorPartOutput($applicator_id, $part_name)
}