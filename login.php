<?php
session_start();
require_once 'config.php';

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, full_name, email, password, role FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $full_name, $email, $hashed_password, $role);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["full_name"] = $full_name;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role;
                            
                            // Get user's full name
                            $name_sql = "SELECT full_name FROM users WHERE id = ?";
                            $name_stmt = $conn->prepare($name_sql);
                            $name_stmt->bind_param("i", $id);
                            $name_stmt->execute();
                            $name_stmt->bind_result($full_name);
                            $name_stmt->fetch();
                            $name_stmt->close();
                            
                            $_SESSION["full_name"] = $full_name;
                            
                            // Close statements and connection
                            mysqli_stmt_close($stmt);
                            mysqli_close($conn);
                            
                            // Redirect to welcome page
                            header("location: welcome.php");
                            exit;
                        } else{
                            // Password is not valid
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else{
                    // Email doesn't exist
                    $login_err = "Invalid email or password.";
                }
            } else{
                $login_err = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else{
            $login_err = "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DEKHOINDIA</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
            height: 70px;
        }

        .navbar.scrolled {
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            max-width: 1200px;
            margin: 0 auto;
            height: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logo i {
            font-size: 1.8rem;
            color: #fff;
        }

        .logo h1 {
            font-size: 1.5rem;
            color: #fff;
            margin: 0;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
            height: 100%;
            align-items: center;
        }

        .nav-links li a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .nav-links li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-links li a.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-container input {
            padding: 0.5rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            outline: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            width: 180px;
        }

        .search-container input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-container input:focus {
            border-color: rgba(255, 255, 255, 0.5);
            width: 200px;
        }

        .search-container button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-container button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        .mobile-menu-btn span {
            display: block;
            width: 25px;
            height: 2px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .mobile-menu-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .user-profile {
            position: relative;
            cursor: pointer;
            padding: 0.5rem;
        }

        .user-profile i {
            font-size: 1.5rem;
            color: #fff;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: #2c3e50;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            padding: 0.5rem;
            display: none;
            min-width: 150px;
        }

        .user-profile:hover .profile-dropdown {
            display: block;
        }

        .profile-dropdown a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .profile-dropdown a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: #2c3e50;
                flex-direction: column;
                padding: 1rem;
                gap: 0.5rem;
                transform: translateY(-100%);
                opacity: 0;
                transition: all 0.3s ease;
                height: auto;
            }

            .nav-links.active {
                transform: translateY(0);
                opacity: 1;
            }

            .nav-links li a {
                display: block;
                padding: 0.8rem;
                text-align: center;
            }

            .search-container {
                display: none;
            }

            .search-container.active {
                display: flex;
                width: 100%;
                margin-top: 1rem;
            }

            .search-container input {
                width: 100%;
            }

            .search-container input:focus {
                width: 100%;
            }
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem;
        }

        .login-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.6s ease forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: #2d3a4b;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e0e7ef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .input-group input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 4px rgba(74,144,226,0.1);
            background: white;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #4a90e2;
            transition: all 0.3s ease;
        }

        .input-group input:focus + i {
            color: #2d3a4b;
            transform: translateY(-50%) scale(1.1);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #e0e7ef;
            border-radius: 5px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remember-me input[type="checkbox"]:checked {
            background: #4a90e2;
            border-color: #4a90e2;
        }

        .remember-me input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 14px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #4a90e2 0%, #2d3a4b 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74,144,226,0.3);
        }

        .login-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s ease;
        }

        .login-btn:active::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .social-login {
            margin-top: 2rem;
            text-align: center;
        }

        .social-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.8rem;
            border: 2px solid #e0e7ef;
            border-radius: 8px;
            background: white;
            color: #2d3a4b;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .social-btn.google:hover {
            border-color: #DB4437;
            color: #DB4437;
        }

        .social-btn.facebook:hover {
            border-color: #4267B2;
            color: #4267B2;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e7ef;
        }

        .register-link a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #2d3a4b;
            text-decoration: underline;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease-in-out;
        }

        .alert-danger {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .has-error input {
            border-color: #ef4444;
        }

        .error-text {
            color: #ef4444;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Loading state */
        .login-btn.loading {
            position: relative;
            color: transparent;
        }

        .login-btn.loading::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <i class="fas fa-landmark"></i>
                <h1>DEKHOINDIA</h1>
            </div>
            <button class="mobile-menu-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.html">Home</a></li>
                <li><a href="heritage.html">Heritage</a></li>
                <li><a href="culture.html">Culture</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li id="loginNav"><a href="login.php" class="active">Login</a></li>
                <li id="profileNav" class="user-profile" style="display:none;">
                    <i class="fas fa-user-circle"></i>
                    <span class="user-name" id="profileName"></span>
                    <div class="profile-dropdown">
                        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </li>
            </ul>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search by state...">
                <button id="searchButton"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Login to explore the rich heritage and culture of India</p>
            </div>

            <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="input-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email Address" value="<?php echo $email; ?>">
                    <?php if(!empty($email_err)): ?>
                        <span class="error-text"><?php echo $email_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="input-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password">
                    <?php if(!empty($password_err)): ?>
                        <span class="error-text"><?php echo $password_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <div class="social-login">
                    <p>Or login with</p>
                    <div class="social-buttons">
                        <button type="button" class="social-btn google">
                            <i class="fab fa-google"></i> Google
                        </button>
                        <button type="button" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                    </div>
                </div>
            </form>

            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Discover the rich heritage and culture of India through our comprehensive platform.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="heritage.html">Heritage</a></li>
                    <li><a href="culture.html">Culture</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Indian Heritage & Culture. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loginBtn = document.querySelector('.login-btn');
            const inputs = document.querySelectorAll('.input-group input');

            // Add floating label effect
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });

                // Check initial value
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });

            // Form submission animation
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) return;

                e.preventDefault();
                loginBtn.classList.add('loading');
                
                // Simulate loading state
                setTimeout(() => {
                    form.submit();
                }, 1000);
            });

            // Password visibility toggle
            const passwordInput = document.querySelector('input[type="password"]');
            const togglePassword = document.createElement('i');
            togglePassword.className = 'fas fa-eye password-toggle';
            togglePassword.style.position = 'absolute';
            togglePassword.style.right = '1rem';
            togglePassword.style.top = '50%';
            togglePassword.style.transform = 'translateY(-50%)';
            togglePassword.style.cursor = 'pointer';
            togglePassword.style.color = '#4a90e2';
            
            passwordInput.parentElement.appendChild(togglePassword);

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.className = `fas fa-eye${type === 'password' ? '' : '-slash'} password-toggle`;
            });

            // Social login buttons hover effect
            const socialBtns = document.querySelectorAll('.social-btn');
            socialBtns.forEach(btn => {
                btn.addEventListener('mouseover', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                btn.addEventListener('mouseout', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html> 