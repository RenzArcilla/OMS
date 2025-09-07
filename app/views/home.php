<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOMS - Home</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/home.css">
    <link rel="stylesheet" href="../../public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="stylesheet" href="../../public/assets/css/components/buttons.css">
</head>
<body>
    
    <?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/sidebar.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/header.php';
    ?>
    
<!-- Animated Background -->
    <div class="background-canvas">
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>
    </div>

        <!-- Hero Section -->
        <div class="container">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="hero-text">
                            <?php 
                            $user_type = $_SESSION['user_type'] ?? null;
                            echo htmlspecialchars(
                                ($user_type && $user_type !== "DEFAULT")
                                    ? 'Welcome, ' . ucwords(strtolower(htmlspecialchars($user_type))) . "!"
                                    : 'Welcome!'
                            ); ?>
                        </span>
                    </div>
                    
                    <h1 class="hero-title">
                        Storage and <br>
                        <span class="gradient-text">Output</span> <br>
                        Monitoring System
                    </h1>
                    
                    <p class="hero-subtitle">
                        Monitor and control your storage and output devices with ease.
                    </p>
                    
                    <div class="buttons-group">
                        <button class="dashboard-btn">
                            <a href="dashboard_applicator.php">Applicator Dashboard</a>
                        </button>
                        <button class="dashboard-btn">
                            <a href="dashboard_machine.php">Machine Dashboard</a>
                        </button>
                        <button class="login-btn"><a href="login.php">Log In</a></button>
                    </div>
                </div>

                <div class="dashboard-container">
                    <div class="dashboard-main">
                        <div class="dashboard-header">
                            <h3 class="dashboard-title">CONTROL CENTER</h3>
                            
                        </div>

                        <div class="dashboard-content">
                            <!-- Status Cards -->
                            <div class="summary-cards">
                                <div class="summary-card">
                                    <div class="summary-value">24</div>
                                    <div class="summary-label">Active Machines</div>
                                    <div class="summary-change positive">+12.5%</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value">16</div>
                                    <div class="summary-label">Operational Units</div>
                                    <div class="summary-change positive">+23.1%</div>
                                </div>
                            </div>

                            <?php include __DIR__ . '/applicator_table.php'; ?>
                            <?php include __DIR__ . '/machine_table.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <script src="../../public/assets/js/sidebar.js"></script>
    <script src="../../public/assets/js/home.js"></script>
</body>
</html>