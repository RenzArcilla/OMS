<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Log In</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <?php require "../controllers/log_in.php"; ?>
</head>
<body>
    <div class="container">
        <div class="left-section"><!-- Left section for logo and navigation -->
            <?php include "../includes/header.php"; ?>
    
            <div class="login-container">
                <h1 class="login-title">Log in</h1>
                
                <form method="POST" action="../controllers/log_in.php">
                    <div class="form-group">
                        <input type="text" name="username" class="form-input" placeholder="Username" required>
                        <input type="password" name="password" class="form-input" placeholder="Password" required>
                    </div>
                    
                    <button type="submit" class="login-btn">Log In</button>
                </form>
                
                <p class="signup-link">or <a href="signin.php">Sign up</a></p>
                
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
                    <p>Access your HEPC account to manage your storage system</p>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>