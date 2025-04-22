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
    return $val ? htmlspecialchars($val) : '<span class="text-gray-400">Not set</span>'; 
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF4B2B',
                        secondary: '#FF416C',
                        dark: '#1A1A1A',
                        darker: '#333333',
                    },
                },
            },
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .gradient-text {
            background: linear-gradient(45deg, #FF4B2B, #FF416C);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-border {
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(45deg, #FF4B2B, #FF416C) border-box;
        }
        .action-btn {
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen relative">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full bg-gradient-to-r from-dark to-darker shadow-lg z-50 h-[70px] transition-all duration-300 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <div class="flex items-center gap-2 cursor-pointer transition-all duration-300 group hover:scale-105">
                <i class="fas fa-landmark text-2xl text-secondary group-hover:rotate-12 transition-transform"></i>
                <h1 class="text-xl text-white font-bold m-0 gradient-text">DEKHOINDIA</h1>
            </div>
            
            <button id="mobileMenuBtn" class="md:hidden flex flex-col gap-1.5 bg-transparent border-none cursor-pointer p-2 hover:scale-105">
                <span class="block w-6 h-0.5 bg-secondary transition-all duration-300 transform" id="line1"></span>
                <span class="block w-6 h-0.5 bg-secondary transition-all duration-300" id="line2"></span>
                <span class="block w-6 h-0.5 bg-secondary transition-all duration-300 transform" id="line3"></span>
            </button>

            <ul id="navLinks" class="hidden md:flex gap-6 list-none m-0 p-0 h-full items-center">
                <li><a href="index.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Home</a></li>
                <li><a href="heritage.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Heritage</a></li>
                <li><a href="culture.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Culture</a></li>
                <li><a href="about.html" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">About</a></li>
                <li><a href="contact.php" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Contact</a></li>
                <li id="profileNav">
                    <div class="relative group">
                        <button class="flex items-center text-white font-medium px-4 py-2 rounded bg-secondary/20 hover:bg-secondary/30 transition-all duration-300">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span id="profileUsername"><?php echo htmlspecialchars($name); ?></span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 hidden group-hover:block">
                            <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                            <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>

            <div id="searchContainer" class="hidden md:flex items-center gap-2">
                <input type="text" id="searchInput" placeholder="Search..." class="px-4 py-2 border border-secondary/20 rounded outline-none transition-all duration-300 bg-white/10 text-white w-44 placeholder:text-white/70 focus:border-secondary/50 focus:w-48 glass-effect hover:-translate-y-1">
                <button id="searchButton" class="bg-secondary/20 text-white border-none px-4 py-2 rounded cursor-pointer transition-all duration-300 hover:bg-secondary/30 glass-effect hover:-translate-y-1">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="fixed top-[70px] left-0 w-full bg-dark shadow-lg z-40 hidden transition-all duration-300 transform origin-top">
        <div class="flex flex-col p-4 space-y-2">
            <a href="index.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Home</a>
            <a href="heritage.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Heritage</a>
            <a href="culture.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Culture</a>
            <a href="about.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">About</a>
            <a href="contact.php" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Contact</a>
            <div id="mobileProfileNav">
                <div class="text-white px-4 py-2">
                    <i class="fas fa-user-circle mr-2"></i>
                    <span id="mobileProfileUsername"><?php echo htmlspecialchars($name); ?></span>
                </div>
                <a href="profile.php" class="block text-white px-4 py-2 rounded bg-secondary/20 hover:bg-secondary/30 transition-all">Profile</a>
                <a href="logout.php" class="block text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Logout</a>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <input type="text" placeholder="Search..." class="px-4 py-2 border border-secondary/20 rounded outline-none bg-white/10 text-white w-full placeholder:text-white/70">
                <button class="bg-secondary/20 text-white border-none px-4 py-2 rounded cursor-pointer hover:bg-secondary/30">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="profile-container relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        <div class="profile-card bg-white rounded-2xl p-8 card-border">
            <div class="profile-icon w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-4xl overflow-hidden">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-name text-3xl font-bold text-center gradient-text mb-2"><?php echo showOrNA($name); ?></div>
            <div class="profile-role text-center text-gray-600 mb-8"><?php echo showOrNA($role); ?></div>
            
            <div class="profile-info grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="info-item bg-gray-50 rounded-lg p-4 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="info-icon w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label text-sm font-semibold text-gray-500">Email</div>
                            <div class="info-value text-gray-800"><?php echo showOrNA($email); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="info-item bg-gray-50 rounded-lg p-4 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="info-icon w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label text-sm font-semibold text-gray-500">Phone</div>
                            <div class="info-value text-gray-800"><?php echo showOrNA($phone); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="info-item bg-gray-50 rounded-lg p-4 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="info-icon w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label text-sm font-semibold text-gray-500">Address</div>
                            <div class="info-value text-gray-800"><?php echo showOrNA($address); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="info-item bg-gray-50 rounded-lg p-4 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="info-icon w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label text-sm font-semibold text-gray-500">Member Since</div>
                            <div class="info-value text-gray-800"><?php echo formatDate($created_at); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="profile-actions flex justify-center gap-4 mt-8">
                <button class="action-btn edit-btn bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300" onclick="window.location.href='edit_profile.php'">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </button>
                <button class="action-btn logout-btn bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-all duration-300" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const line1 = document.getElementById('line1');
            const line2 = document.getElementById('line2');
            const line3 = document.getElementById('line3');
            
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                
                // Hamburger animation
                line1.classList.toggle('rotate-45');
                line1.classList.toggle('translate-y-1.5');
                line2.classList.toggle('opacity-0');
                line3.classList.toggle('-rotate-45');
                line3.classList.toggle('-translate-y-1.5');
            });
        });
    </script>
</body>
</html>