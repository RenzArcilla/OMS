<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOMS - Sign Up</title>
    <link rel="icon" href="/SOMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/typography.css">
    <link rel="stylesheet" href="../../public/assets/css/signup.css">
    <link rel="stylesheet" href="../../public/assets/css/components/cards.css">
    <link rel="stylesheet" href="../../public/assets/css/components/buttons.css">
</head>
<body>
    <div class="card">
        <div class="card-content">
            <div class="card-title">
                <h1>Create Account</h1>
                <p class="subtitle">Create an account to get started</p>
            </div>
        <form method="POST" action="../controllers/sign_up.php" autocomplete="off">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="Enter your username" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="form-input" placeholder="First name" required>
                </div>
                
                <div class="form-lastname">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Last name" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm your password" required>
            </div>
            
            <button class="btn-primary" type="submit" id="submitBtn">Create Account</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Log in here</a></p>
    </div>

</body>
</html>