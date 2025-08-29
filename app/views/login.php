<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Log In</title>
    <link rel="icon" href="/SOMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/typography.css">
    <link rel="stylesheet" href="../../public/assets/css/login.css">
    <?php require_once "../controllers/log_in.php"; ?>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="header">

                <nav class="nav"> 
                </nav>
            </div>

            <div class="login-container">
                <form class="login-form" action="../controllers/log_in.php" method="POST">
                    <h1 class="login-title">Welcome Back</h1>
                    <p class="login-subtitle">Sign in to your account to continue</p>

                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username"
                            class="form-input" 
                            placeholder="Enter your username"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="form-input" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <div class="forgot-password">
                        <a href="forgot_password.php">Forgot your password?</a>
                    </div>

                    <button type="submit" class="login-btn">
                        Sign In
                    </button>

                    <div class="divider">
                        <span></span>
                    </div>

                    <div class="signup-link"> 
                        Don't have an account? <a href="signup.php">Create one here</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="right-section">
            
            <div class="right-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                <img src="../../public/assets/images/hepc_white.png" alt="Logo" style="width: 500%; max-width: 650px;">
            </div>
        
        </div>
    </div>
</body>
</html> 