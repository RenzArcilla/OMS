<?php
/*
    This is the home page of the application. It serves as the landing page for logged-in users.
    It includes a hero section with a brief description of the system and a call to action for accessing the dashboard or signing up.

session_start();
=======

// Start session and check if user is logged in
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
*/

include_once __DIR__ . '/../includes/header.php'; // Include the header file for the navigation and logo
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Hero</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/hero.css">
</head>
<body>
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Storage & Output <span>Monitoring</span> System</h1>
                    <p>Real-time monitoring and analytics for your storage infrastructure. Track performance, optimize resources, and ensure maximum efficiency with our comprehensive monitoring solution.</p>
                    
                    <div class="cta-buttons">
                        <a href="#login" class="btn btn-primary">Access Dashboard</a>
                    </div>
                </div>

                <div class="dashboard-preview">
                    <div class="dashboard-header">
                        <h3>System Overview</h3>
                        <div class="status-indicators">
                            <div class="status-dot"></div>
                            <div class="status-dot warning"></div>
                            <div class="status-dot error"></div>
                        </div>
                    </div>
                    
                    <div class="metrics-grid">
                        <div class="metric-card">
                            <div class="metric-value">Input Data Here</div>
                            <div class="metric-label">Applicator</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">Input Data Here</div>
                            <div class="metric-label">Machine</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../assets/js/hero.js"></script>
=======
    <h1>Storage and Output Monitoring System</h1>
    <a href="add.php">Add-Record</a>
</body>
</html>