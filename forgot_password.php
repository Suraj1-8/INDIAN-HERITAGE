<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to index page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = "";
$email_err = "";
$success_msg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
        
        // Check if email exists in the database
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Email exists, generate reset token
                    $reset_token = bin2hex(random_bytes(32));
                    $reset_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    
                    // Store reset token in database
                    $update_sql = "UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?";
                    if($update_stmt = mysqli_prepare($link, $update_sql)){
                        mysqli_stmt_bind_param($update_stmt, "sss", $reset_token, $reset_expiry, $email);
                        mysqli_stmt_execute($update_stmt);
                        
                        // Send reset email (in a real application, you would use a proper email service)
                        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $reset_token;
                        $to = $email;
                        $subject = "Password Reset Request";
                        $message = "Hello,\n\nYou have requested to reset your password. Click the link below to reset it:\n\n" . $reset_link . "\n\nThis link will expire in 1 hour.\n\nIf you did not request this, please ignore this email.\n\nRegards,\nYour Website Team";
                        $headers = "From: noreply@yourwebsite.com";
                        
                        // For demonstration purposes, we'll just show the link
                        $success_msg = "If an account exists with that email, a password reset link has been sent. For demonstration, here's the link: " . $reset_link;
                    }
                } else {
                    // Don't reveal if email exists or not for security reasons
                    $success_msg = "If an account exists with that email, a password reset link has been sent.";
                }
            } else{
                $email_err = "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Heritage Website</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        .wrapper {
            width: 360px;
            padding: 20px;
            margin: 0 auto;
            margin-top: 50px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            width: 100%;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .alert {
            margin-top: 20px;
        }
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="page-header">
            <h2>Forgot Password</h2>
            <p>Enter your email to reset your password</p>
        </div>
        
        <?php 
        if(!empty($success_msg)){
            echo '<div class="alert alert-success">' . $success_msg . '</div>';
        }
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Reset Password">
            </div>
        </form>
        
        <div class="back-to-login">
            <p>Remember your password? <a href="login.php">Login here</a></p>
        </div>
    </div>    
</body>
</html> 