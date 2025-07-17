<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Log In</title>
    <link rel="stylesheet" href="app/assets/css/login.css">
    <script src="app/assets/js/login.js"></script>
    <?php include 'app/controllers/sign_in.php'; ?>
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
                <h1 class="login-title">Log in</h1>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <input type="text" name="username" class="form-input" placeholder="Username" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="password" name="password" class="form-input" placeholder="Password" required>
                    </div>
                    
                    <button type="submit" class="login-btn">Log In</button>
                </form>
                
                <p class="signup-link">or <a href="#">Sign up</a></p>
                
                <div class="admin-toggle">
                    <div class="toggle-switch" id="adminToggle">
                        <div class="toggle-slider"></div>
                    </div>
                    <span class="toggle-label">Log in as Administrator</span>
                </div>
            </div>
        </div>

        <div class="right-section">
            <div class="right-content">
                <div class="right-text">
                    <h2>Welcome Back</h2>
                    <p>Access your HEPC account to manage your healthcare services and connect with our professional network.</p>
                </div>
            </div>
        </div>
    </div>

    

    
</body>
</html>