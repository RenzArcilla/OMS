<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="/SOMS/public/assets/css/base/typography.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/forgot_password.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/cards.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/buttons.css">
</head>
<body>
    <div class="card">
        <div class="card-content">
            <div class="card-title">
                <h1>Forgot Password</h1>
                <p class="subtitle">Enter your username and we'll send you instructions to reset your password</p>
            </div>
            
            <div class="success-message" id="successMessage">
                ✓ Password reset instructions have been sent to your email address.
            </div>
            
            <div class="error-message" id="errorMessage">
                ✗ Username not found. Please check your username and try again.
            </div>
            
            <form id="forgotPasswordForm" action="forgot_password.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    <div class="input-highlight"></div>
                </div>
                
                <button class="btn-primary" type="submit" id="submitBtn">Reset Password</button>
                
                <a href="login.php" class="back-link">← Back to Login</a>
            </form>
        </div>
    </div>
</body>
</html>