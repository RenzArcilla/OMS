<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOMS - Storage and Output Monitoring System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/upload.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/hero.css">
    <link rel="stylesheet" href="../assets/css/metrics.css">
    <link rel="stylesheet" href="../assets/css/navigation.css">
    <link rel="stylesheet" href="../assets/css/upload.css">
    <link rel="stylesheet" href="../assets/css/dashboard_preview.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="nav-container">
                <div class="logo">
                    <div class="logo-icon">Ꝏ</div>
                    <span>HEPC</span>
                </div>
                <nav class="nav">
                    <a href="?page=home" class="<?php echo ($activePage ?? '') === 'home' ? 'active' : ''; ?>">Home</a>
                    <a href="?page=upload" class="<?php echo ($activePage ?? '') === 'upload' ? 'active' : ''; ?>">Upload</a>
                    <a href="?page=login" class="<?php echo ($activePage ?? '') === 'login' ? 'active' : ''; ?>">Login</a>
                </nav>
            </div>
        </div>
    </header>
