<?php
    // PHP backend logic for sign in

    // Reteieve form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Sanitize input
        $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : Null;
        $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : Null;
        $password = isset($_POST['password']) ? trim($_POST['password']) : Null;
        $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['password']) : Null;

        // Check if fields are empty
        if (empty($firstname) || empty($lastname) || empty($password) || empty($confirm_password)) {
            echo "<script>alert('Please fill in all fields.');</script>";
        } else {
            //  Connect to the database
            include '../includes/db.php'; 
            
            // Try to register the user
            include_once '../models/CREATE_user.php';

            $result = createUser($firstname, $lastname, $password, $confirm_password);
            
            // Check if user creation was successful
            if ($result === true) {
                echo "<script>alert('Registration successful!');</script>";
                // Redirect to login page or home page
                header("Location: ../templates/login.php");
                exit();
            } elseif (is_string($result)) {
                echo $result; // Display error message from createUser function
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        }
    }
    ?>