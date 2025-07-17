<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Log In</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .left-section {
            flex: 1;
            background: #f8f8f8;
            padding: 40px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .right-section {
            flex: 1;
            background: linear-gradient(135deg, #8B0000 0%, #B22222 50%, #DC143C 100%);
            position: relative;
            overflow: hidden;
        }

        .right-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 60px;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .logo-icon {
            width: 60px;
            height: 40px;
            background: linear-gradient(45deg, #FF4500, #FF6347);
            border-radius: 8px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .nav {
            display: flex;
            gap: 30px;
        }

        .nav a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav a:hover {
            color: #DC143C;
        }

        .nav a.active {
            color: #DC143C;
            border-bottom: 2px solid #DC143C;
            padding-bottom: 2px;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 36px;
            font-weight: 300;
            color: #333;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 16px;
            background: #fff;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #DC143C;
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8B0000 0%, #DC143C 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #DC143C 0%, #FF1493 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
        }

        .signup-link {
            text-align: left;
            color: #666;
            font-size: 14px;
        }

        .signup-link a {
            color: #DC143C;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .admin-toggle {
            margin-top: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-switch {
            position: relative;
            width: 50px;
            height: 24px;
            background: #ccc;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .toggle-switch.active {
            background: #DC143C;
        }

        .toggle-slider {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .toggle-switch.active .toggle-slider {
            transform: translateX(26px);
        }

        .toggle-label {
            color: #999;
            font-size: 14px;
        }

        .right-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .right-text {
            text-align: center;
            color: white;
            max-width: 300px;
        }

        .right-text h2 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .right-text p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .right-section {
                min-height: 200px;
            }
            
            .left-section {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                gap: 20px;
                margin-bottom: 40px;
            }
            
            .nav {
                gap: 20px;
            }
        }
    </style>
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

    <script>
        // Admin toggle functionality
        const adminToggle = document.getElementById('adminToggle');
        const toggleLabel = document.querySelector('.toggle-label');
        
        adminToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            if (this.classList.contains('active')) {
                toggleLabel.textContent = 'Administrator Mode';
                toggleLabel.style.color = '#DC143C';
            } else {
                toggleLabel.textContent = 'Log in as Administrator';
                toggleLabel.style.color = '#999';
            }
        });

        // Form submission handling
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.querySelector('input[name="username"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const isAdmin = adminToggle.classList.contains('active');
            
            if (username && password) {
                alert(`Login attempt:\nUsername: ${username}\nAdmin Mode: ${isAdmin ? 'Yes' : 'No'}`);
            }
        });
    </script>

    <?php
    // PHP backend logic for handling login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        
        // Basic validation
        if (empty($username) || empty($password)) {
            echo "<script>alert('Please fill in all fields.');</script>";
        } else {
            // Here you would typically:
            // 1. Sanitize input
            // 2. Check against database
            // 3. Hash password verification
            // 4. Set session variables
            // 5. Redirect to dashboard
            
            // For demo purposes:
            if ($username === 'admin' && $password === 'password') {
                echo "<script>alert('Login successful! Redirecting to dashboard...');</script>";
                // header('Location: dashboard.php');
                // exit();
            } else {
                echo "<script>alert('Invalid credentials. Please try again.');</script>";
            }
        }
    }
    ?>
</body>
</html>