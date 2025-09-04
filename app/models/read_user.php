<?php
/*
    This file defines a READ operation for user login.
    It retrieves user data from the database and checks if the user account exists.
*/

// Include the database connection
require_once __DIR__ . '/../includes/db.php';


function loginUser($username, $password) {
    /*
        Function to log in a user by checking the username and password.

        Args:
        - $username: string, the username of the user.
        - $password: string, the password of the user.

        Returns:
        - array: user data if login is successful.
        - false: if user does not exist or password is incorrect.
        - string: error message on database failure.
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


/*
function getUsers(int $limit = 10, int $offset = 0): array {
    
        Function to fetch a list of users from the database with pagination. Used to display
        users in manage_users.php.
        It prepares and executes a SELECT query that fetches users ordered by most recent,
        and returns them as an associative array.

        Args:
        - $limit: Maximum number of rows to fetch (default is 10).
        - $offset: Number of rows to skip (default is 0), used for pagination.

        Returns:
        - Array of users (associative arrays) on success.
    

    global $pdo;
    // Prepare the SQL statement with placeholders for limit and offset
    $stmt = $pdo->prepare("
        SELECT user_id, username, first_name, last_name, user_type
        FROM users
        ORDER BY user_id DESC 
        LIMIT :limit OFFSET :offset
    ");

    // Bind pagination parameters securely
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query and return the results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
} */


function getUserByUsername($username) {
    /*
        Function to fetch a user by username.

        Args:
        - $username: string, the username of the user to fetch.

        Returns:
        - array: user data if found.
        - false: if user does not exist.
        - string (alert message): on database failure.
    */
        
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return false;
        } else {
            // $result is an array with user data
            return $result;
        }

    } catch (PDOException $e) {
        error_log("Error checking duplicate username: " . $e->getMessage());
        return "Database error while checking username. Please try again.";
        exit;
    }
}


function searchUsers(string $search = '', string $role = 'all', int $limit = 20, int $offset = 0): array {
    /*
        Function to search users from the database with optional filters and pagination.
        It applies a search query across username, first name, and last name,
        and can also filter users based on their role. Results are paginated.

        Parameters:
            string $search - The search keyword (optional).
            string $role   - The role filter (optional, default = 'all').
            int $limit     - Number of rows to fetch per page (default = 20).
            int $offset    - Starting point for rows (default = 0).

        Returns:
            array - A list of users matching the search and filter criteria.
    */

    global $pdo;

    // Escape LIKE wildcards if search is used
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\%', '\_'], $search);
    }

    $sql = "
        SELECT user_id, username, first_name, last_name, user_type
        FROM users
        WHERE 1=1
    ";

    $params = [];

    // Apply search filter if provided
    if (!empty($search)) {
        $sql .= " AND (username LIKE :search OR first_name LIKE :search OR last_name LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // Apply role filter if not set to 'all'
    if ($role !== 'all') {
        $sql .= " AND user_type = :role";
        $params[':role'] = $role;
    }

    $sql .= " ORDER BY user_id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    // Bind normal parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    // Bind pagination parameters (must be integers)
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Count users for pagination with optional search and role filters.
 */
function countUsers(string $search = '', string $role = 'all'): int {
    global $pdo;

    // Escape LIKE wildcards if search is used
    if (!empty($search)) {
        $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
    }

    $sql = "SELECT COUNT(*) AS total FROM users WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (username LIKE :search OR first_name LIKE :search OR last_name LIKE :search)";
        $params[':search'] = "%$search%";
    }

    if ($role !== 'all') {
        $sql .= " AND user_type = :role";
        $params[':role'] = $role;
    }

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($row['total'] ?? 0);
}