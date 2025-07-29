<?php
/*
    This file is part of log in operation for user accounts.
    This is a READ operation for user accounts.
    It retrieves user data from the database and checks if the user account exists.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';


function loginUser($username, $password) {
    /*
    Function to log in a user by checking the username and password.
  
    Args:
    - $username: The username of the user.
    - $password: The password of the user.

    Returns:
    - False if the user does not exist or the password is incorrect.
    - User data array if the login is successful.
    - String containing error message and redirect using JS <alert>.
    */

    global $pdo;

    try {
        // Prepare SQL select query with first_name and last_name
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");

        // Bind parameters
        $stmt->bindParam(':username', $username);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and verify password
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Successful login, return user data
        } else {
            return false; // Invalid credentials
        }
    } catch (PDOException $e) {
        // Log error and return an error message on failure
        error_log("Database Error: " . $e->getMessage());
        return "Database error occurred: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}