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
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i data-lucide="menu"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <button class="active" onclick="setActiveTab('dashboard')">
                            <i data-lucide="bar-chart-3"></i>
                            <span>Dashboard</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button onclick="setActiveTab('users')">
                            <i data-lucide="users"></i>
                            <span>Users</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button onclick="setActiveTab('add_replace')">
                            <i data-lucide="add_replace"></i>
                            <span>Add/Replace</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button onclick="setActiveTab('machine')">
                            <i data-lucide="machine"></i>
                            <span>Machine</span>
                        </button>
                    <li class="nav-item">
                        <button onclick="setActiveTab('applicator')">
                            <i data-lucide="applicator"></i>
                            <span>Applicator</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button onclick="setActiveTab('maintenance')">
                            <i data-lucide="maintenance"></i>
                            <span>Maintenance</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button onclick="setActiveTab('logs')">
                            <i data-lucide="logs"></i>
                            <span>Logs</span>
                        </button>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-avatar">U</div>
                <div class="user-info">
                    <p>Username</p>
                    <p class=".user-name">user-name-here</p>
                </div>
            </div>
        </div>
    <script src="../../public/assets/js/side_bar.js"></script>
</body>
</html>