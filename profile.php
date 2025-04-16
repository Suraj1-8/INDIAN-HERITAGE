<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'config.php';
$user_id = $_SESSION['id'];

// Get user data
$sql = "SELECT full_name, email, role, phone, address, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $role, $phone, $address, $created_at);
$stmt->fetch();
$stmt->close();

$conn->close();

function showOrNA($val) { 
    return $val ? htmlspecialchars($val) : '<span style="color:#aaa">Not set</span>'; 
}

function formatDate($date) {
    return $date ? date('F j, Y, g:i a', strtotime($date)) : 'Never';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - DEKHOINDIA</title>
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
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(60,60,120,0.1);
            padding: 2rem;
            text-align: center;
            position: relative;
            transition: transform 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
        }

        .profile-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #4a90e2 0%, #2d3a4b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 3.5rem;
            box-shadow: 0 4px 15px rgba(74,144,226,0.3);
        }

        .profile-name {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2d3a4b;
            margin-bottom: 0.5rem;
        }

        .profile-role {
            color: #4a90e2;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            padding: 0.3rem 1rem;
            background: rgba(74,144,226,0.1);
            border-radius: 1rem;
            display: inline-block;
        }

        .profile-info {
            text-align: left;
            margin-top: 2rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.8rem;
            border-radius: 0.8rem;
            transition: background 0.3s ease;
        }

        .info-item:hover {
            background: #f8fafc;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: rgba(74,144,226,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: #4a90e2;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }

        .info-value {
            color: #2d3a4b;
            font-weight: 500;
        }

        .profile-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .action-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 2rem;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .edit-btn {
            background: #4a90e2;
            color: white;
        }

        .edit-btn:hover {
            background: #2d3a4b;
            transform: translateY(-2px);
        }

        .logout-btn {
            background: #fee2e2;
            color: #ef4444;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 0 1rem;
            }
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
                        <a href="profile.php" class="active"><i class="fas fa-user"></i> Profile</a>
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
    
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-name"><?php echo showOrNA($name); ?></div>
            <div class="profile-role"><?php echo showOrNA($role); ?></div>
            
            <div class="profile-info">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo showOrNA($email); ?></div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Phone</div>
                        <div class="info-value"><?php echo showOrNA($phone); ?></div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Address</div>
                        <div class="info-value"><?php echo showOrNA($address); ?></div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Member Since</div>
                        <div class="info-value"><?php echo formatDate($created_at); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="profile-actions">
                <!-- <button class="action-btn edit-btn" onclick="window.location.href='edit_profile.php'">
                    <i class="fas fa-edit"></i> Edit Profile
                </button> -->
                <button class="action-btn logout-btn" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 