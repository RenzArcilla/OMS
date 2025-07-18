<?php
/*
    This file is part of the application for user registration.
    It handles the CREATE operation for user accounts.

*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 


function createUser($first_name, $last_name, $password, $confirm_password) {
    global $pdo;

    /*
    Function to create a new user in the database.
    It verifies that password and confirm_password match,
    hashes the password, and inserts the user data into the 'users' table.

    Args:
    - $first_name: The user's first name.
    - $last_name: The user's last name.
    - $password: The password of the user (to be hashed).
    - $confirm_password: The confirmation password, must match $password.

    Returns:
    - true on success, false on failure.
    */

    // Password confirmation check
    if ($password !== $confirm_password) {
        return "<script>alert('Password Mismatch!.');</script>"; 
    }

    try {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL insert query
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, password, user_type)
            VALUES (:first_name, :last_name, :password, 'Default')
        ");

        // Bind user input values to placeholders
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the statement
        $stmt->execute();

        // Success
        return true;
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
    }   
}