<?php
/*
    This script handles the addition of a new machine to the database.
    It retrieves form data, sanitizes it, and inserts it into the database.
*/

// Start session and check if user is logged in
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../templates/login.php");
    exit();
}

// Retrieve form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $control_no = isset($_POST['control_no']) ? strtoupper(trim($_POST['control_no'])) : null;
    $description = isset($_POST['description']) ? strtoupper(trim($_POST['description'])) : null;
    $model = isset($_POST['model']) ? strtoupper(trim($_POST['model'])) : null;
    $machine_maker = isset($_POST['machine_maker']) ? strtoupper(trim($_POST['machine_maker'])) : null;
    $serial_no = empty($_POST['serial_no']) ? 'NO RECORD' : strtoupper(trim($_POST['serial_no']));
    $invoice_no = empty($_POST['invoice_no']) ? 'NO RECORD' : strtoupper(trim($_POST['invoice_no']));


    // Check if fields are empty
    if (empty($control_no) || empty($description) || empty($model) || empty($machine_maker)) {
        echo "<script>alert('Please fill in all required fields.');
            window.location.href = '../templates/add_entry.php';</script>";

    } else if ($description !== 'AUTOMATIC' && $description !== 'SEMI-AUTOMATIC') {
        echo "<script>alert('Invalid selection for description.');
            window.location.href = '../templates/add_entry.php';</script>";

    } else {
        // Include the model to handle database operations
        include_once '../models/CREATE_machine.php';

        // Try to create the applicator
        $result = createMachine($control_no, $description, $model,
                                $machine_maker, $serial_no, $invoice_no);

        // Check if applicator creation was successful
        if ($result === true) {
            echo "<script>alert('Machine added successfully!');
                window.location.href = '../templates/add_entry.php';</script>";
            exit();
        } elseif (is_string($result)) {
            echo $result; // Display error message from createMachine function
        } else {
            echo "<script>alert('Failed to add Machine. Please try again.');
                window.location.href = '../templates/add_entry.php';</script>";
        }
    }
}