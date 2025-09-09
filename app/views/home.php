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
    <title>OMS - Home</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/home.css">
    <link rel="stylesheet" href="../../public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="stylesheet" href="../../public/assets/css/components/buttons.css">
</head>
<body>
    
    <?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/sidebar.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/header.php';
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
                        Output <br>
                        <span class="gradient-text">Monitoring</span> <br>
                        System
                    </h1>
                    
                    <p class="hero-subtitle">
                        Monitor and control your output devices with ease.
                    </p>
                    
                    <div class="buttons-group">
                        <button class="dashboard-btn" onclick="window.location.href='dashboard_applicator.php'">
                            Applicator Dashboard
                        </button>
                        <button class="dashboard-btn" onclick="window.location.href='dashboard_machine.php'">
                            Machine Dashboard
                        </button>
                        <?php if (empty($_SESSION['user_type'])): ?>
                            <button class="login-btn" onclick="window.location.href='login.php'">
                                Log In
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dashboard-container">
                    <div class="dashboard-main">
                        <div class="dashboard-header">
                            <h3 class="dashboard-title">CONTROL CENTER              <div style="font-size: 17px; color: #888;" id="dashboardTime"></div></h3>
                        </div>

                        <div class="dashboard-content">
                            <?php 
                                $active_applicators = require '../controllers/count_active_applicators.php'; 
                                $active_machines = require '../controllers/count_active_machines.php';
                            ?>
                            <!-- Status Cards -->
                            <div class="summary-cards">
                                <div class="summary-card">
                                    <div class="summary-value"><?php echo $active_machines; ?></div>
                                    <div class="summary-label">Active Machines</div>
                                    <div class="summary-change positive"></div>
                                </div>
                                    <div class="summary-card">
                                        <div class="summary-value"><?php echo $active_applicators; ?></div>
                                        <div class="summary-label">Active Applicators</div>
                                        <div class="summary-change positive"></div>
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
    <script src="../../public/assets/js/utils/enter.js"></script>
    <script src="../../public/assets/js/utils/exit.js"></script>
</body>
</html>