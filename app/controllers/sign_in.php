<?php
    // PHP backend logic for handling login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        
        // Basic validation
        if (empty($username) || empty($password)) {
            echo "<script>alert('Please fill in all fields.');</script>";
        } else {
            // Here you would typically:
            // 1. Sanitize input
            // 2. Check against database
            // 3. Hash password verification
            // 4. Set session variables
            // 5. Redirect to dashboard
            
            // For demo purposes:
            if ($username === 'admin' && $password === 'password') {
                echo "<script>alert('Login successful! Redirecting to dashboard...');</script>";
                // header('Location: dashboard.php');
                // exit();
            } else {
                echo "<script>alert('Invalid credentials. Please try again.');</script>";
            }
        }
    }
    ?>