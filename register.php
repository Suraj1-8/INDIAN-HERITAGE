<?php
session_start();
require_once 'config.php';

// Define variables and initialize with empty values
$full_name = $email = $password = $confirm_password = $phone = $address = "";
$full_name_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate full name
    if(empty(trim($_POST["full_name"]))){
        $full_name_err = "Please enter your full name.";
    } else{
        $full_name = trim($_POST["full_name"]);
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Get additional fields
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    
    // Check input errors before inserting in database
    if(empty($full_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (full_name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sssss", $param_full_name, $param_email, $param_password, $param_phone, $param_address);
            
            // Set parameters
            $param_full_name = $full_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_phone = $phone;
            $param_address = $address;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php?registered=1");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Indian Heritage & Culture</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .register-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 600px;
            position: relative;
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

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h2 {
            color: #2d3a4b;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #666;
            font-size: 1.1rem;
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

        .password-strength {
            height: 4px;
            background: #e0e7ef;
            margin-top: 0.5rem;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .password-strength.weak .password-strength-bar {
            width: 33.33%;
            background: #ef4444;
        }

        .password-strength.medium .password-strength-bar {
            width: 66.66%;
            background: #f59e0b;
        }

        .password-strength.strong .password-strength-bar {
            width: 100%;
            background: #10b981;
        }

        .password-requirements {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }

        .requirement i {
            font-size: 0.8rem;
        }

        .requirement.met {
            color: #10b981;
        }

        .requirement.unmet {
            color: #666;
        }

        .register-btn {
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

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74,144,226,0.3);
        }

        .register-btn::after {
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

        .register-btn:active::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .register-btn.loading {
            color: transparent;
        }

        .register-btn.loading::before {
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
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e7ef;
        }

        .login-link a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #2d3a4b;
            text-decoration: underline;
        }

        .error-text {
            color: #ef4444;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .floating-shape {
            position: absolute;
            background: rgba(74,144,226,0.1);
            border-radius: 50%;
            animation: float 20s infinite;
        }

        .shape1 { width: 100px; height: 100px; top: 10%; left: 10%; animation-delay: 0s; }
        .shape2 { width: 150px; height: 150px; top: 60%; right: 10%; animation-delay: -5s; }
        .shape3 { width: 70px; height: 70px; top: 40%; left: 50%; animation-delay: -10s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(50px, 50px) rotate(90deg); }
            50% { transform: translate(0, 100px) rotate(180deg); }
            75% { transform: translate(-50px, 50px) rotate(270deg); }
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 30px;
            height: 30px;
            background: white;
            border: 2px solid #4a90e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            color: #4a90e2;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .progress-step.active .step-number {
            background: #4a90e2;
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            color: #666;
        }

        .progress-line {
            position: absolute;
            top: 15px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #e0e7ef;
            z-index: 0;
        }

        .progress-line-fill {
            height: 100%;
            width: 0;
            background: #4a90e2;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <!-- <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <i class="fas fa-landmark"></i>
                <h1>Indian Heritage</h1>
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Home</a></li>
                <li><a href="heritage.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Heritage</a></li>
                <li><a href="culture.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Culture</a></li>
                <li><a href="about.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">About</a></li>
                <li><a href="contact.php" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Contact</a></li>
                <li><a href="login.php" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Login</a></li>
            </ul>
        </div>
    </nav> -->

    <!-- Register Section -->
    <div class="register-container">
        <div class="register-box">
            <div class="register-header">
                <h2>Create Your Account</h2>
                <p>Join us to explore the rich heritage and culture of India</p>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="input-group <?php echo (!empty($full_name_err)) ? 'has-error' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <input type="text" name="full_name" placeholder="Full Name" value="<?php echo $full_name; ?>">
                    <?php if(!empty($full_name_err)): ?>
                        <span class="error-text"><?php echo $full_name_err; ?></span>
                    <?php endif; ?>
                </div>

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

                <div class="input-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm Password">
                    <?php if(!empty($confirm_password_err)): ?>
                        <span class="error-text"><?php echo $confirm_password_err; ?></span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone" placeholder="Phone Number (Optional)" value="<?php echo $phone; ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="address" placeholder="Address (Optional)" value="<?php echo $address; ?>">
                </div>

                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- <footer class="site-footer">
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
                    <li><a href="contact.php">Contact</a></li>
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
    </footer> -->

    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const registerBtn = document.querySelector('.register-btn');
            const inputs = document.querySelectorAll('.input-group input');
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');

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

                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });

            // Add password strength indicator
            const strengthMeter = document.createElement('div');
            strengthMeter.className = 'password-strength';
            const strengthBar = document.createElement('div');
            strengthBar.className = 'password-strength-bar';
            strengthMeter.appendChild(strengthBar);
            passwordInput.parentElement.appendChild(strengthMeter);

            // Add password requirements
            const requirements = document.createElement('div');
            requirements.className = 'password-requirements';
            requirements.innerHTML = ``;
            passwordInput.parentElement.appendChild(requirements);

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                const requirements = {
                    length: password.length >= 8,
                    number: /\d/.test(password),
                    special: /[!@#$%^&*]/.test(password),
                    capital: /[A-Z]/.test(password)
                };

                Object.keys(requirements).forEach(req => {
                    const element = document.querySelector(`[data-requirement="${req}"]`);
                    if (requirements[req]) {
                        strength++;
                        element.classList.add('met');
                        element.classList.remove('unmet');
                        element.querySelector('i').className = 'fas fa-check-circle';
                    } else {
                        element.classList.remove('met');
                        element.classList.add('unmet');
                        element.querySelector('i').className = 'fas fa-circle';
                    }
                });

                strengthMeter.className = 'password-strength ' + 
                    (strength <= 1 ? 'weak' : strength <= 2 ? 'medium' : 'strong');
            }

            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Password visibility toggle
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

            // Form submission animation
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) return;

                e.preventDefault();
                registerBtn.classList.add('loading');
                
                setTimeout(() => {
                    form.submit();
                }, 1000);
            });

            // Add floating shapes
            const shapes = document.createElement('div');
            shapes.className = 'floating-shapes';
            shapes.innerHTML = `
                <div class="floating-shape shape1"></div>
                <div class="floating-shape shape2"></div>
                <div class="floating-shape shape3"></div>
            `;
            document.querySelector('.register-container').appendChild(shapes);
        });
    </script>
</body>
</html> 