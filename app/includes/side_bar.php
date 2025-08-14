<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Bar</title>
    <link rel="stylesheet" href="../../public/assets/css/side_bar.css">
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-title">SOMS</h2>
        </div>

        <nav class="sidebar-nav" id="sidebar-nav">
            <ul class="sidebar-menu">
                <li><a href="#" data-page="dashboard" >Dashboard</a></li>
                <li><a href="../views/dashboard_machine.php" data-page="machine">Machine</a></li>
                <li><a href="../views/dashboard_applicator.php" data-page="applicator">Applicator</a></li>
                <li><a href="../views/add_entry.php" data-page="add-replace">Add/Replace</a></li>
                <li><a href="../views/record_output.php" data-page="record">Record</a></li>
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
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="overlay"></div>

    <!-- Toggle Button -->
    <button class="toggle-btn" id="toggleBtn">☰</button>

    <script src="../../public/assets/js/side_bar.js"></script>
</body>
</html>