<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Log In</title>
    <link rel="stylesheet" href="../assets/css/admin_login.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="login-container">
                <div class="welcome-content">
                    <h2>Welcome Back</h2>
                    <p>We're excited to have you return. Your secure dashboard awaits with all your personalized content and tools.</p>
                </div>
            </div>
            
            <div class="toggle-container">
                <div class="toggle-switch" onclick="toggleTheme()">
                    <div class="toggle-slider"></div>
                </div>
                <span class="toggle-label">Dark Mode</span>
            </div>
        </div>
        
        <div class="right-section">
            <div class="right-content">
                <div class="login-form">
                    <h1 class="login-title">Sign In</h1>
                    <p class="login-subtitle">Enter your credentials to access your account</p>
                    
                    <form>
                        <div class="form-group">
                            <input type="text" id="username" class="form-input" placeholder="Enter your username" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="password" id="password" class="form-input" placeholder="Enter your password" required>
                        </div>
                        
                        <button type="submit" class="login-btn">Sign In</button>
                    </form>
                    
                    <div class="divider">
                        <span>or</span>
                    </div>
                    
                    <div class="signup-link">
                        Don't have an account? <a href="#">Create one now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>