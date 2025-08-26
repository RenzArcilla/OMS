<?php
/*
    This file is part of the application for user registration.
    It handles the CREATE operation for user accounts in the database.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php'; 


function createUser($first_name, $last_name, $username, $password, $role = "DEFAULT") {
    /*
        Function to create a new user in the database.
        It hashes the password, and inserts the user data into the 'users' table.

        Args:
        - $first_name: The user's first name.
        - $last_name: The user's last name.
        - $username: The user's username.
        - $password: The password of the user (to be hashed).
        - $role: DEFAULT, TOOLKEEPER, ADMIN

        Returns:
        - User data array if the login is successful.
        - String containing error message.
    */

    global $pdo;

    // Check if username exists
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $checkStmt->bindParam(':username', $username);
    $checkStmt->execute();
    if ($checkStmt->fetchColumn() > 0) {
        return "Username already taken. Please choose another.";
    }

    try {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL insert query
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, password, username, user_type)
            VALUES (:first_name, :last_name, :password, :user_name, :role)
        ");

        // Bind user input values to placeholders
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':user_name', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        // Execute the statement
        $stmt->execute();

        // Get the last inserted user ID
        $userId = $pdo->lastInsertId();

        // Fetch inserted user data
        $stmt2 = $pdo->prepare("SELECT user_id, username, first_name, user_type FROM users WHERE user_id = :id");
        $stmt2->bindParam(':id', $userId);
        $stmt2->execute();
        $user = $stmt2->fetch(PDO::FETCH_ASSOC);

        // Success
        return $user;
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }   
}