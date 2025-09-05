<?php
/*
    Contains functions to update user passwords.
    Handles password verification and updates for the current user.
*/

require_once __DIR__ . '/../includes/db.php';

function verifyCurrentPassword($user_id, $current_password) {
    /*
        Function to verify the current password before updating.

        Args:
        - $user_id: int, the ID of the user.
        - $current_password: string, the current password to verify.

        Returns:
        - bool: true if password is correct
        - string: error message on failure.
    */

    global $pdo;

    try {
        $sql = "SELECT password FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return "User not found.";
        }
        
        if (!password_verify($current_password, $user['password'])) {
            return "Current password is incorrect.";
        }
        
        return true;

    } catch (PDOException $e) {
        error_log("Error verifying current password: " . $e->getMessage());
        return "Error verifying password. Please try again.";
    }
}

function updateUserPassword($user_id, $new_password) {
    /*
        Function to update user password in the database.

        Args:
        - $user_id: int, the ID of the user to update.
        - $new_password: string, the new password to set.

        Returns:
        - bool: true on success
        - string: error message on failure.
    */

    global $pdo;

    try {
        // Hash the new password
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = :password WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':password', $password_hash);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return "Failed to update password. Please try again.";
        }

    } catch (PDOException $e) {
        error_log("Error updating user password: " . $e->getMessage());
        return "Error updating password: " . $e->getMessage();
    }
}
?>
