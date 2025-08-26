<?php
/*
    Contains functions to edit user outputs.
    Handles standard columns and custom JSON parts for each user.
*/

require_once __DIR__ . '/../includes/db.php';

function updateUser($user_id, $username, $first_name, $last_name, $password, $user_type) {
    /*
        Function to update user information in the database.

        Args:
        - $user_id: int, the ID of the user to update.
        - $username: string, the new username for the user.
        - $first_name: string, the new first name for the user.
        - $last_name: string, the new last name for the user.
        - $password: string|null, the new password for the user (hashed).
        - $user_type: string, the new user type for the user.

        Returns:
        - bool: true on success
        - string: error message on failure.
    */

    global $pdo;

    try {
        if (!empty($password)) {
            // Update with new password
            $sql = "
                UPDATE users 
                SET username = :username, 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    password = :password, 
                    user_type = :user_type 
                WHERE user_id = :user_id";
        } else {
            // Update without password
            $sql = "
                UPDATE users 
                SET username = :username, 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    user_type = :user_type 
                WHERE user_id = :user_id";
        }

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':user_type', $user_type);

        if (!empty($password)) {
            $stmt->bindParam(':password', $password);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return "Failed to update user. Please try again.";
        }

    } catch (PDOException $e) {
        error_log("Error updating user information: " . $e->getMessage());
        return "Error updating user information: " . $e->getMessage();
    }
}
