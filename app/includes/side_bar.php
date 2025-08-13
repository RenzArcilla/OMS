<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Bar</title>
    <link rel="stylesheet" href="../../public/assets/css/side_bar.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2 class="sidebar-title">SOMS</h2>
                <button class="toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                    ☰
                </button>
            </div>

            <nav class="sidebar-nav" id="sidebar-nav">
                <ul class="sidebar-menu">
                    <li><a href="#" data-page="dashboard" class="active">Dashboard</a></li>
                    <li><a href="../views/dashboard_machine.php" data-page="machine">Machine</a></li>
                    <li><a href="../views/dashboard_applicator.php" data-page="applicator">Applicator</a></li>
                    <li><a href="../views/add_entry.php" data-page="add-replace">Add/Replace</a></li>
                    <li><a href="../views/admin_manage_user.php" data-page="users">Users</a></li>
                    <li><a href="../views/logs.php" data-page="log">Log</a></li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-avatar">U</div>
                <div class="user-info">
                    <p>Username</p>
                    <p class="user-name">user-name-here</p>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area" id="content-area">
            <!-- Content will be loaded here -->
            <div class="welcome-message">
                <h1>Welcome to SOMS</h1>
                <p>Select a menu item from the sidebar to get started.</p>
            </div>
            <div class="page-content" id="dashboard">
                <h1>About Us</h1>
                <p>This is the about page content. Here you can add information about your company, team, or yourself.</p>
                <p>The sidebar remains functional across all pages, allowing users to navigate seamlessly.</p>
            </div>
            <div class="page-content" id="machine">
                <h1>About Us</h1>
                <p>This is the about page content. Here you can add information about your company, team, or yourself.</p>
                <p>The sidebar remains functional across all pages, allowing users to navigate seamlessly.</p>
            </div>
        </div>
        
    </div>
    <script src="../../public/assets/js/side_bar.js"></script>
</body>
</html>