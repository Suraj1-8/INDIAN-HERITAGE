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
        .welcome-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem;
        }

        .welcome-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .welcome-header h1 {
            color: #2d3a4b;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .welcome-message {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #4a90e2 0%, #2d3a4b 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74,144,226,0.3);
        }

        .btn-logout {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            margin-left: 1rem;
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
                <li id="loginNav" style="display:none;"><a href="login.php">Login</a></li>
                <li id="profileNav" class="user-profile">
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

    <div class="welcome-container">
        <div class="welcome-box">
            <div class="welcome-header">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!</h1>
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

    <!-- Footer -->
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
    </footer>

    <script src="script.js"></script>
</body>
</html> 