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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #1A1A1A;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .gradient-text {
            background: linear-gradient(45deg, #FF4B2B, #FF416C);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 75, 43, 0.2);
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 75, 43, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="glass-effect rounded-2xl p-8 shadow-xl">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-2 mb-4 floating">
                    <i class="fas fa-landmark text-3xl text-white"></i>
                    <h1 class="text-2xl font-bold gradient-text">DEKHOINDIA</h1>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
                <p class="text-gray-300">Sign in to continue your journey</p>
            </div>

            <?php 
            if(!empty($login_err)){
                echo '<div class="alert">' . $login_err . '</div>';
            }        
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm" class="space-y-6">
                <div>
                    <label for="email" class="block text-white mb-2">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="email" id="email" name="email" required
                            class="input-focus w-full pl-10 pr-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:border-secondary"
                            placeholder="Enter your email" value="<?php echo $email; ?>">
                    </div>
                    <span class="error-text"><?php echo $email_err; ?></span>
                </div>

                <div>
                    <label for="password" class="block text-white mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="password" name="password" required
                            class="input-focus w-full pl-10 pr-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:border-secondary"
                            placeholder="Enter your password">
                    </div>
                    <span class="error-text"><?php echo $password_err; ?></span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">Remember me</label>
                    </div>
                    <a href="forgot_password.php" class="text-sm text-secondary hover:text-primary transition-colors">Forgot password?</a>
                </div>

                <button type="submit" 
                    class="btn-hover w-full py-3 px-4 bg-gradient-to-r from-primary to-secondary text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-opacity-50">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-300">Don't have an account? 
                    <a href="register.php" class="text-secondary hover:text-primary transition-colors">Sign up</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('loginForm');
            const loginBtn = document.querySelector('.btn-hover');
            const inputs = document.querySelectorAll('.input-focus');
            const passwordInput = document.querySelector('input[type="password"]');
            const passwordStrength = document.querySelector('.password-strength div');
            const togglePassword = document.querySelector('.password-toggle');

            // Real-time validation
            function validateEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function getPasswordStrength(password) {
                let strength = 0;
                if (password.length > 5) strength += 20;
                if (password.length > 8) strength += 20;
                if (/[A-Z]/.test(password)) strength += 20;
                if (/[0-9]/.test(password)) strength += 20;
                if (/[^A-Za-z0-9]/.test(password)) strength += 20;
                return strength;
            }

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const inputGroup = this.parentElement;
                    const errorText = inputGroup.querySelector('.error-text');

                    if (this.type === 'email') {
                        if (validateEmail(this.value)) {
                            inputGroup.classList.add('valid');
                            inputGroup.classList.remove('invalid');
                            errorText.style.display = 'none';
                        } else {
                            inputGroup.classList.add('invalid');
                            inputGroup.classList.remove('valid');
                            errorText.textContent = 'Invalid email format';
                            errorText.style.display = 'block';
                        }
                    }

                    if (this.type === 'password') {
                        const strength = getPasswordStrength(this.value);
                        passwordStrength.style.width = `${strength}%`;
                        passwordStrength.style.background = 
                            strength < 40 ? '#e74c3c' :
                            strength < 80 ? '#f1c40f' : '#2ecc71';
                    }
                });
            });

            // Password visibility toggle
            if (togglePassword) {
                togglePassword.addEventListener('click', () => {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                    togglePassword.className = `fas fa-eye${type === 'password' ? '' : '-slash'} password-toggle`;
                });
            }

            // Form submission
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) return;
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;
            });
        });
    </script>
</body>
</html>