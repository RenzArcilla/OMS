<?php
/*
    This is the landing page for the OMS application.
    This is the main entry point when users access the application.
*/


error_reporting(E_ERROR | E_PARSE); // only show fatal errors & parse errors
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMS - Landing Page</title>
    <link rel="stylesheet" href="assets/css/landing_page.css"> 
</head>
<body>
    <div class="gate left"></div>
    <div class="gate right"></div>
    
    <div class="logo-container" onclick="openGates()">
        <div class="logo-card">
            <h1 class="logo-title">
                <img src="assets/images/hepc_black.png" alt="HEPC Logo" style="height: 64px;">
            </h1>
        </div>  
    </div>
    <script src="assets/js/landing_page.js"></script>
</body>
</html>