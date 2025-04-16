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
            transform: scale(1.2);
        }

        .contact-item h3 {
            color: #2d3a4b;
            margin-bottom: 0.25rem;
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
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
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
        }

        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(74,144,226,0.3);
            background: #4a90e2;
            color: white;
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
                <li><a href="contact.php" class="active">Contact</a></li>
                <li id="loginNav"><a href="login.php">Login</a></li>
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

    <main class="contact-container">
        <div class="contact-info">
            <h2>Contact Us</h2>
            <p>Have questions or feedback? We'd love to hear from you!</p>
            
            <div class="contact-details">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Address</h3>
                        <p>123 Heritage Street, New Delhi, India</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Phone</h3>
                        <p>+91 1234567890</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@heritageculture.com</p>
                    </div>
                </div>
            </div>
            
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
        
        <div class="contact-form-container">
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
            
            <form method="POST" action="" class="contact-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Government Links</h3>
                <ul class="footer-links">
                    <li><a href="https://www.incredibleindia.org/" target="_blank">Incredible India</a></li>
                    <li><a href="https://tourism.gov.in/" target="_blank">Ministry of Tourism</a></li>
                    <li><a href="https://www.indiaculture.nic.in/" target="_blank">Ministry of Culture</a></li>
                    <li><a href="https://asi.nic.in/" target="_blank">Archaeological Survey of India</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 Heritage & Culture. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.contact-form');
            const submitBtn = document.querySelector('.submit-btn');
            const inputs = document.querySelectorAll('.form-group input, .form-group textarea');

            // Add floating shapes
            const shapes = document.createElement('div');
            shapes.className = 'floating-shapes';
            shapes.innerHTML = `
                <div class="shape shape1"></div>
                <div class="shape shape2"></div>
                <div class="shape shape3"></div>
            `;
            document.querySelector('.contact-container').appendChild(shapes);

            // Add loading animation to submit button
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
                }, 1000);
            });

            // Add floating label effect
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

            // Add character counter for message
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

            // Add ripple effect to contact items
            const contactItems = document.querySelectorAll('.contact-item');
            contactItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const ripple = document.createElement('div');
                    ripple.className = 'ripple';
                    ripple.style.cssText = `
                        position: absolute;
                        background: rgba(74,144,226,0.2);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;

                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size/2;
                    const y = e.clientY - rect.top - size/2;

                    ripple.style.width = ripple.style.height = `${size}px`;
                    ripple.style.left = `${x}px`;
                    ripple.style.top = `${y}px`;

                    this.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Add copy to clipboard functionality
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
        });

        // Add keyframe animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to { transform: scale(4); opacity: 0; }
            }
            @keyframes spin {
                to { transform: translate(-50%, -50%) rotate(360deg); }
            }
            @keyframes fadeOut {
                to { opacity: 0; transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html> 