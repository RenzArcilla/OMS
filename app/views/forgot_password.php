<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../../public/assets/css/base/typography.css">
    <link rel="stylesheet" href="../../public/assets/css/forgot_password.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Header Section -->
            <div class="form-title">
                <h1>Forgot Password</h1>
                <p class="subtitle">Enter your username and we'll send you instructions to reset your password</p>
            </div>
            
            <!-- Messages Section -->
            <div class="success-message" id="successMessage">
                Password reset instructions have been sent to your email address.
            </div>
            
            <div class="error-message" id="errorMessage">
                Username not found. Please check your username and try again.
            </div>
            

            <div class="content-area">
                <form id="forgotPasswordForm" action="forgot_password.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    
                    <button type="submit" class="btn-primary" id="submitBtn">Reset Password</button>
                    <a href="login.php" class="btn-secondary">← Back to Login</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>