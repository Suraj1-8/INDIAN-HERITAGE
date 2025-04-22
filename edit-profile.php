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

$errors = [];
$success = '';
$name = $email = $phone = $address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    if ($phone && !preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Valid 10-digit phone number is required';
    }

    if (empty($errors)) {
        $sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Profile updated successfully!';
            $_SESSION['name'] = $name;
        } else {
            $errors[] = 'Failed to update profile. Please try again.';
        }
        $stmt->close();
    }
} else {
    // Load existing user data
    $sql = "SELECT full_name, email, phone, address FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $email, $phone, $address);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();

function showOrNA($val) { 
    return $val ? htmlspecialchars($val) : ''; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - DEKHOINDIA</title>
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
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(255, 75, 43, 0.2);
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

    <!-- Edit Profile Section -->
    <div class="edit-profile-container relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        <div class="edit-profile-card bg-white rounded-2xl p-8 card-border">
            <div class="profile-icon w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-4xl overflow-hidden">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-name text-3xl font-bold text-center gradient-text mb-2">Edit Profile</div>
            <div class="profile-role text-center text-gray-600 mb-8">Update your information</div>

            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <p><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="name" class="block text-sm font-semibold text-gray-500 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo showOrNA($name); ?>" 
                           class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="block text-sm font-semibold text-gray-500 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo showOrNA($email); ?>" 
                           class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="block text-sm font-semibold text-gray-500 mb-2">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo showOrNA($phone); ?>" 
                           class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" 
                           pattern="[0-9]{10}" placeholder="1234567890">
                </div>
                
                <div class="form-group">
                    <label for="address" class="block text-sm font-semibold text-gray-500 mb-2">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo showOrNA($address); ?>" 
                           class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                </div>

                <div class="form-actions flex justify-center gap-4 mt-8 md:col-span-2">
                    <button type="submit" class="action-btn bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                    <button type="button" class="action-btn bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-all duration-300" 
                            onclick="window.location.href='profile.php'">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                </div>
            </form>
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