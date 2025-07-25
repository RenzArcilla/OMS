<?php
/*
    This script handles the addition of a new applicator to the database.
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
    $terminal_no = isset($_POST['terminal_no']) ? strtoupper(trim($_POST['terminal_no'])) : null;
    $description = isset($_POST['description']) ? strtoupper(trim($_POST['description'])) : null;
    $wire_type = isset($_POST['wire_type']) ? strtoupper(trim($_POST['wire_type'])) : null;
    $terminal_maker = isset($_POST['terminal_maker']) ? strtoupper(trim($_POST['terminal_maker'])) : null;
    $applicator_maker = isset($_POST['applicator_maker']) ? strtoupper(trim($_POST['applicator_maker'])) : null;
    $serial_no = empty($_POST['serial_no']) ? 'NO RECORD' : strtoupper(trim($_POST['serial_no']));
    $invoice_no = empty($_POST['invoice_no']) ? 'NO RECORD' : strtoupper(trim($_POST['invoice_no']));


    // Check if fields are empty
    if (empty($control_no) || empty($terminal_no) || empty($description) || 
        empty($wire_type) || empty($terminal_maker) || empty($applicator_maker)) {
        echo "<script>alert('Please fill in all required fields.');
            window.location.href = '../templates/add_entry.php';</script>";

    } else if ($description !== 'SIDE' && $description !== 'END') {
        echo "<script>alert('Invalid selection for description.');
            window.location.href = '../templates/add_entry.php';</script>";
    
    } else if ($wire_type !== 'BIG' && $wire_type !== 'SMALL') {
        echo "<script>alert('Invalid selection for wire type.');
            window.location.href = '../templates/add_entry.php';</script>";

    } else {
        // Include the model to handle database operations
        include_once '../models/CREATE_applicator.php';

        // Try to create the applicator
        $result = createApplicator($control_no, $terminal_no, $description, 
                                    $wire_type, $terminal_maker, $applicator_maker, 
                                    $serial_no, $invoice_no);

        // Check if applicator creation was successful
        if ($result === true) {
            echo "<script>alert('Applicator added successfully!');
                window.location.href = '../templates/add_entry.php';</script>";
            exit();
        } elseif (is_string($result)) {
            echo $result; // Display error message from createApplicator function
        } else {
            echo "<script>alert('Failed to add applicator. Please try again.');
                window.location.href = '../templates/add_entry.php';</script>";
        }
    }
}