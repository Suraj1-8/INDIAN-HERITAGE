<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Get user details from database
require_once 'config.php';
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - DEKHOINDIA</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="search.js"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .navbar {
            position: fixed;
            top: 0; left: 0;
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
            background: rgba(255,255,255,0.1);
        }
        .nav-links li a.active {
            background: rgba(255,255,255,0.2);
        }
        .search-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .search-container input {
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 5px;
            width: 180px;
            outline: none;
        }
        .search-container input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        .search-container button {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            padding: 0.5rem;
            min-width: 150px;
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }
        .user-profile:hover .profile-dropdown {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .profile-dropdown a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        .profile-dropdown a:hover {
            background: rgba(255,255,255,0.1);
        }
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }
            .nav-links {
                flex-direction: column;
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: #2c3e50;
                transform: translateY(-100%);
                opacity: 0;
                transition: all 0.3s ease;
            }
            .nav-links.active {
                transform: translateY(0);
                opacity: 1;
            }
            .search-container {
                display: none;
            }
        }
        /* Welcome box */
        .welcome-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .welcome-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            animation: slideIn 1.2s ease forwards;
            opacity: 0;
        }
        @keyframes slideIn {
            0% { opacity: 0; transform: translateY(50px);}
            100% { opacity: 1; transform: translateY(0);}
        }
        .welcome-header h1 {
            font-size: 2.5rem;
            color: #2d3a4b;
        }
        .welcome-header h1.typing {
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #2d3a4b;
            animation: typing 3s steps(30, end), blink 0.75s step-end infinite;
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink {
            from, to { border-color: transparent; }
            50% { border-color: #2d3a4b; }
        }
        .welcome-message {
            font-size: 1.2rem;
            margin: 1rem 0 2rem;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #4a90e2 0%, #2d3a4b 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin: 0.5rem;
            position: relative;
            overflow: hidden;
        }
        .btn::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.2);
            transition: all 0.4s ease;
        }
        .btn:hover::after {
            left: 0;
        }
        .btn-logout {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
</head>

<body>
    

    <div class="welcome-container">
        <div class="welcome-box">
            <div class="welcome-header">
                <h1 class="typing">Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!</h1>
            </div>
            <div class="welcome-message">
                <p>You have successfully logged in to Indian Heritage & Culture.</p>
            </div>
            <div class="welcome-actions">
                <a href="index.html" class="btn">Go to Home</a>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
    </div>

    <script>
        const navbar = document.querySelector('.navbar');
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.getElementById('navLinks');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuBtn.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    </script>

</body>
</html>
