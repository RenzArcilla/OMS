<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOMS - Storage and Output Monitoring System</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="header">
                <div class="logo">
                    <div class="logo-icon">∞</div>
                    <span>HEPC</span>
                </div>
                <nav class="nav">
                    <a href="#" class="nav-link">HOME</a>
                    <a href="#" class="nav-link">ABOUT US</a>
                    <a href="#" class="nav-link">CONTACT</a>
                    <a href="#" class="nav-link active">LOG IN</a>
                </nav>
            </div>
            
            <div class="login-container">
                <h2 class="login-title">System Access</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-input" placeholder="Enter your username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" required>
                    </div>
                    
                    <button type="submit" class="login-btn">Access Dashboard</button>
                </form>
                <p class="signup-link">Don't have an account? <a href="#">Sign up here</a></p>
                
                <div class="admin-toggle">
                    <div class="toggle-switch" id="adminToggle">
                        <div class="toggle-slider"></div>
                    </div>
                    <span class="toggle-label">Admin Mode</span>
                </div>
            </div>
        </div>
        
        <div class="right-section">
            <div class="right-content">
                <div class="right-text">
                    <h2>Welcome to SOMS</h2>
                    <p>Storage and Output Monitoring System. Real-time monitoring and analytics for your storage infrastructure.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/login.js"></script>
</body>
</html>
