<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password Confirmation</title>
    <link rel="stylesheet" href="../../public/assets/css/base/typography.css">
        <link rel="stylesheet" href="../../public/assets/css/forgot_password.css">
        <link rel="stylesheet" href="../../public/assets/css/forgot_password.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Header Section -->
            <div class="form-title">
                <h1>Set New Password</h1>
                <p class="subtitle">Create a strong password to secure your account</p>
            </div>
            
            <!-- Messages Section -->
            <div class="success-message" id="successMessage">
                Your password has been successfully updated!
            </div>
            
            <div class="error-message" id="errorMessage">
                Please check your password requirements and try again.
            </div>
            
            <!-- Content Area - Add your content here -->
            <div class="content-area">
                <form id="newPasswordForm" action="reset_password.php" method="post">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="new_password" name="new_password" placeholder="Enter your new password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                👁️
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span id="strengthText">Password strength</span>
                        </div>
                        <div class="password-requirements">
                            <div class="requirement" id="lengthReq">
                                <span class="requirement-icon">○</span>
                                At least 8 characters
                            </div>
                            <div class="requirement" id="uppercaseReq">
                                <span class="requirement-icon">○</span>
                                One uppercase letter
                            </div>
                            <div class="requirement" id="lowercaseReq">
                                <span class="requirement-icon">○</span>
                                One lowercase letter
                            </div>
                            <div class="requirement" id="numberReq">
                                <span class="requirement-icon">○</span>
                                One number
                            </div>
                            <div class="requirement" id="specialReq">
                                <span class="requirement-icon">○</span>
                                One special character
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                👁️
                            </button>
                        </div>
                        <div class="password-match" id="passwordMatch">
                            Passwords match ✓
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary" id="submitBtn" disabled>Update Password</button>
                    <a href="login.php" class="btn-secondary">← Back to Login</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>