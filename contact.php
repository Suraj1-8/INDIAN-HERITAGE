<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "heritage_culture";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle contact form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
            
    if ($stmt->execute()) {
        $success = "Message sent successfully!";
    } else {
        $error = "Error sending message. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - DEKHOINDIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
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
                    animation: {
                        'fade-in': 'fadeIn 1s ease-in-out',
                        'floating': 'floating 3s ease-in-out infinite',
                        'shine': 'shine 3s infinite',
                        'pulse': 'pulse 2s infinite',
                        'glow': 'glow 1.5s ease-in-out infinite',
                        'typing': 'typing 2s steps(20) forwards, blink 0.7s step-end infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        floating: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        shine: {
                            '0%': { transform: 'translateX(-100%) rotate(30deg)' },
                            '100%': { transform: 'translateX(100%) rotate(30deg)' },
                        },
                        pulse: {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' },
                        },
                        glow: {
                            '0%, 100%': { boxShadow: '0 0 5px rgba(74,144,226,0.3)' },
                            '50%': { boxShadow: '0 0 20px rgba(74,144,226,0.7)' },
                        },
                        typing: {
                            '0%': { width: '0%' },
                            '100%': { width: '100%' },
                        },
                        blink: {
                            '0%, 100%': { borderColor: 'transparent' },
                            '50%': { borderColor: 'black' },
                        },
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
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .contact-info {
            flex: 1;
            min-width: 300px;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .contact-info h2 {
            color: #2d3a4b;
            font-size: 2rem;
            margin-bottom: 1rem;
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid #2d3a4b;
            animation: typing 2s steps(20) forwards, blink 0.7s step-end infinite;
        }
        .contact-details {
            margin: 2rem 0;
        }
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 10px;
            background: #f8fafc;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .contact-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: white;
        }
        .contact-item i {
            font-size: 1.5rem;
            color: #4a90e2;
            transition: transform 0.3s ease;
        }
        .contact-item:hover i {
            transform: scale(1.2) rotate(10deg);
        }
        .contact-item h3 {
            color: #2d3a4b;
            margin-bottom: 0.25rem;
        }
        .contact-item-detail {
            opacity: 0;
            max-height: 0;
            transition: all 0.3s ease;
        }
        .contact-item.active .contact-item-detail {
            opacity: 1;
            max-height: 100px;
            margin-top: 0.5rem;
        }
        .contact-form-container {
            flex: 2;
            min-width: 400px;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2d3a4b;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e7ef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 4px rgba(74,144,226,0.1);
            background: white;
            animation: glow 1.5s ease-in-out infinite;
        }
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        .form-group .validation-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .form-group.valid .validation-icon {
            opacity: 1;
            color: #10b981;
        }
        .form-group.invalid .validation-icon {
            opacity: 1;
            color: #ef4444;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e0e7ef;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(135deg, #4a90e2, #2d3a4b);
            width: 0;
            transition: width 0.3s ease;
        }
        .submit-btn {
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
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74,144,226,0.3);
        }
        .submit-btn::after {
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
        .submit-btn:active::after {
            transform: translate(-50%, -50%) scale(1);
        }
        .success-message,
        .error-message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            animation: slideIn 0.5s ease;
        }
        .success-message {
            background: #dcfce7;
            border: 1px solid #10b981;
            color: #065f46;
        }
        .error-message {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            position: relative;
        }
        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4a90e2;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(74,144,226,0.3);
            background: #4a90e2;
            color: white;
        }
        .social-link .tooltip {
            position: absolute;
            top: -2.5rem;
            background: #2d3a4b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.8rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }
        .social-link:hover .tooltip {
            opacity: 1;
            transform: translateY(0);
        }
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        .shape {
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
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
            }
            .contact-form-container,
            .contact-info {
                min-width: 100%;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 transition-colors duration-300">
    <!-- Navbar (Unchanged) -->
    <nav class="fixed top-0 left-0 w-full bg-gradient-to-r from-dark to-darker shadow-lg z-50 h-[70px] transition-all duration-300 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <div class="flex items-center gap-2 cursor-pointer transition-all duration-300 group hover:scale-105">
                <i class="fas fa-landmark text-2xl text-secondary group-hover:rotate-12 transition-transform animate-floating"></i>
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
                <li id="loginNav"><a href="login.php" class="nav-link text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">Login</a></li>
                <li id="profileNav" class="hidden">
                    <div class="relative group">
                        <button id="profileButton" class="flex items-center text-white font-medium px-4 py-2 rounded hover:bg-secondary/10 transition-all duration-300">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span id="profileUsername"></span>
                        </button>
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 hidden">
                            <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                            <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Mobile Menu (Unchanged) -->
    <div id="mobileMenu" class="fixed top-[70px] left-0 w-full bg-dark shadow-lg z-40 hidden transition-all duration-300 transform origin-top">
        <div class="flex flex-col p-4 space-y-2">
            <a href="index.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Home</a>
            <a href="heritage.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Heritage</a>
            <a href="culture.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Culture</a>
            <a href="about.html" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">About</a>
            <a href="contact.php" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Contact</a>
            <a href="login.php" id="mobileLoginNav" class="text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Login</a>
            <div id="mobileProfileNav" class="hidden">
                <div class="text-white px-4 py-2">
                    <i class="fas fa-user-circle mr-2"></i>
                    <span id="mobileProfileUsername"></span>
                </div>
                <a href="profile.php" class="block text-white px-4 py-2 rounded hover:bg-secondary/10 transition-all">Profile</a>
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

    <main class="contact-container">
        <div class="contact-info">
            <h2>Contact Us</h2>
            <p>Have questions or feedback? We'd love to hear from you!</p>
            
            <div class="contact-details">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Address</h3>
                        <p>LPU, Phagwara, Punjab, India</p>
                        <div class="contact-item-detail">
                            <p>Visit us at our campus location!</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Phone</h3>
                        <p>+91 6206239371</p>
                        <div class="contact-item-detail">
                            <p>Available 9 AM - 5 PM IST</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@heritageculture.com</p>
                        <div class="contact-item-detail">
                            <p>Expect a response within 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="social-links">
                <a href="#" class="social-link">
                    <i class="fab fa-facebook"></i>
                    <span class="tooltip">Follow us on Facebook</span>
                </a>
                <a href="#" class="social-link">
                    <i class="fab fa-twitter"></i>
                    <span class="tooltip">Tweet us</span>
                </a>
                <a href="#" class="social-link">
                    <i class="fab fa-instagram"></i>
                    <span class="tooltip">Check our Instagram</span>
                </a>
                <a href="#" class="social-link">
                    <i class="fab fa-linkedin"></i>
                    <span class="tooltip">Connect on LinkedIn</span>
                </a>
            </div>
        </div>
        
        <div class="contact-form-container">
            <div class="progress-bar">
                <div class="progress-bar-fill"></div>
            </div>

            <?php if (isset($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="https://api.web3forms.com/submit" class="contact-form">
                <input type="hidden" name="access_key" value="459cd497-abea-4cb5-9bdc-235014608be8">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>">
                    <i class="fas fa-check-circle validation-icon"></i>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>">
                    <i class="fas fa-check-circle validation-icon"></i>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                    <i class="fas fa-check-circle validation-icon"></i>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                    <i class="fas fa-check-circle validation-icon"></i>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </main>

    <!-- Footer (Unchanged) -->
    <footer class="bg-gradient-to-r from-dark to-darker text-white py-12 glass-effect">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-landmark text-2xl mr-2 animate-floating"></i> <span class="gradient-text">DEKHOINDIA</span>
                    </h3>
                    <p class="text-gray-300 mb-4">Exploring the rich heritage and vibrant culture of India.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-2xl text-gray-300 hover:text-secondary transition-colors hover:-translate-y-1"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl text-gray-300 hover:text-secondary transition-colors hover:-translate-y-1"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-2xl text-gray-300 hover:text-secondary transition-colors hover:-translate-y-1"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl text-gray-300 hover:text-secondary transition-colors hover:-translate-y-1"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">Explore</h3>
                    <ul class="space-y-2">
                        <li><a href="heritage.html" class="text-gray-400 hover:text-white transition-colors">Heritage Sites</a></li>
                        <li><a href="culture.html" class="text-gray-400 hover:text-white transition-colors">Cultural Events</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Travel Guides</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Photo Gallery</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">Government Links</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.incredibleindia.org/" target="_blank" class="text-gray-400 hover:text-white transition-colors">Incredible India</a></li>
                        <li><a href="https://tourism.gov.in/" target="_blank" class="text-gray-400 hover:text-white transition-colors">Ministry of Tourism</a></li>
                        <li><a href="https://www.indiaculture.nic.in/" target="_blank" class="text-gray-400 hover:text-white transition-colors">Ministry of Culture</a></li>
                        <li><a href="https://asi.nic.in/" target="_blank" class="text-gray-400 hover:text-white transition-colors">Archaeological Survey</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i> New Delhi, India</li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-phone-alt mr-2"></i> +91 1234567890</li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-2"></i> info@dekhoindia.com</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>© 2023 DEKHOINDIA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.contact-form');
            const submitBtn = document.querySelector('.submit-btn');
            const inputs = document.querySelectorAll('.form-group input, .form-group textarea');
            const progressBarFill = document.querySelector('.progress-bar-fill');
            const contactItems = document.querySelectorAll('.contact-item');

            // Add floating shapes
            const shapes = document.createElement('div');
            shapes.className = 'floating-shapes';
            shapes.innerHTML = `
                <div class="shape shape1"></div>
                <div class="shape shape2"></div>
                <div class="shape shape3"></div>
            `;
            document.querySelector('.contact-container').appendChild(shapes);

            // Form submission with confetti
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) return;

                e.preventDefault();
                submitBtn.style.color = 'transparent';
                submitBtn.style.pointerEvents = 'none';
                
                const loader = document.createElement('div');
                loader.className = 'button-loader';
                loader.style.cssText = `
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
                `;
                submitBtn.appendChild(loader);

                setTimeout(() => {
                    form.submit();
                    if (document.querySelector('.success-message')) {
                        confetti({
                            particleCount: 100,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                    }
                }, 1000);
            });

            // Real-time form validation and progress bar
            const updateProgress = () => {
                const totalFields = inputs.length;
                let filledFields = 0;
                inputs.forEach(input => {
                    if (input.value.trim()) filledFields++;
                    const formGroup = input.parentElement;
                    if (input.checkValidity() && input.value.trim()) {
                        formGroup.classList.add('valid');
                        formGroup.classList.remove('invalid');
                    } else if (input.value.trim()) {
                        formGroup.classList.add('invalid');
                        formGroup.classList.remove('valid');
                    } else {
                        formGroup.classList.remove('valid', 'invalid');
                    }
                });
                const progress = (filledFields / totalFields) * 100;
                progressBarFill.style.width = `${progress}%`;
            };
            inputs.forEach(input => {
                input.addEventListener('input', updateProgress);
            });

            // Floating label effect
            inputs.forEach(input => {
                const label = input.previousElementSibling;
                input.addEventListener('focus', () => {
                    label.style.transform = 'translateY(-25px) scale(0.8)';
                    label.style.color = '#4a90e2';
                });
                input.addEventListener('blur', () => {
                    if (!input.value) {
                        label.style.transform = '';
                        label.style.color = '';
                    }
                });
                if (input.value) {
                    label.style.transform = 'translateY(-25px) scale(0.8)';
                }
            });

            // Character counter for message
            const messageArea = document.querySelector('#message');
            const counter = document.createElement('div');
            counter.className = 'char-counter';
            counter.style.cssText = `
                position: absolute;
                bottom: 0.5rem;
                right: 0.5rem;
                font-size: 0.8rem;
                color: #666;
            `;
            messageArea.parentElement.style.position = 'relative';
            messageArea.parentElement.appendChild(counter);
            messageArea.addEventListener('input', function() {
                const remaining = 1000 - this.value.length;
                counter.textContent = `${remaining} characters remaining`;
                counter.style.color = remaining < 100 ? '#ef4444' : '#666';
            });

            // Toggle contact item details
            contactItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    this.classList.toggle('active');
                });
            });

            // Copy to clipboard functionality
            const contactDetails = document.querySelectorAll('.contact-item p');
            contactDetails.forEach(detail => {
                detail.style.cursor = 'pointer';
                detail.title = 'Click to copy';
                detail.addEventListener('click', function(e) {
                    e.stopPropagation();
                    navigator.clipboard.writeText(this.textContent);
                    const tooltip = document.createElement('div');
                    tooltip.textContent = 'Copied!';
                    tooltip.style.cssText = `
                        position: absolute;
                        background: #4a90e2;
                        color: white;
                        padding: 0.5rem 1rem;
                        border-radius: 5px;
                        font-size: 0.9rem;
                        pointer-events: none;
                        animation: fadeOut 1s forwards;
                    `;
                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = `${rect.left}px`;
                    tooltip.style.top = `${rect.top - 30}px`;
                    document.body.appendChild(tooltip);
                    setTimeout(() => tooltip.remove(), 1000);
                });
            });

            // Mobile Menu Toggle (Unchanged)
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const line1 = document.getElementById('line1');
            const line2 = document.getElementById('line2');
            const line3 = document.getElementById('line3');
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('animate-fadeInDown');
                line1.classList.toggle('rotate-45');
                line1.classList.toggle('translate-y-1.5');
                line2.classList.toggle('opacity-0');
                line3.classList.toggle('-rotate-45');
                line3.classList.toggle('-translate-y-1.5');
            });

            // Profile dropdown toggle (Unchanged)
            const profileButton = document.getElementById('profileButton');
            const profileDropdown = document.getElementById('profileDropdown');
            let dropdownTimeout;
            profileButton.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
                if (dropdownTimeout) {
                    clearTimeout(dropdownTimeout);
                }
                dropdownTimeout = setTimeout(function() {
                    profileDropdown.classList.add('hidden');
                }, 3000);
            });
            document.addEventListener('click', function() {
                profileDropdown.classList.add('hidden');
            });
            profileDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Check login status (Unchanged)
            fetch('check_login.php')
                .then(response => response.json())
                .then(data => {
                    const loginNav = document.getElementById('loginNav');
                    const profileNav = document.getElementById('profileNav');
                    const profileUsername = document.getElementById('profileUsername');
                    const mobileLoginNav = document.getElementById('mobileLoginNav');
                    const mobileProfileNav = document.getElementById('mobileProfileNav');
                    const mobileProfileUsername = document.getElementById('mobileProfileUsername');
                    if (data.loggedin) {
                        loginNav.classList.add('hidden');
                        profileNav.classList.remove('hidden');
                        profileUsername.textContent = data.username;
                        mobileLoginNav.classList.add('hidden');
                        mobileProfileNav.classList.remove('hidden');
                        mobileProfileUsername.textContent = data.username;
                    } else {
                        loginNav.classList.remove('hidden');
                        profileNav.classList.add('hidden');
                        mobileLoginNav.classList.remove('hidden');
                        mobileProfileNav.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>