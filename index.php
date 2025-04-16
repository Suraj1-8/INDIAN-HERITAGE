<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian Heritage & Culture</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .slider-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .slider {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .slide-content {
            max-width: 800px;
            padding: 2rem;
            z-index: 2;
        }

        .slide-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .slide-content p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .slider-nav {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 1rem;
            z-index: 3;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot.active {
            background: white;
            transform: scale(1.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="slider-container">
        <div class="slider">
            <div class="slide active" style="background-image: url('images/slide1.jpg');">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h1 class="fade-in">Discover India's Rich Heritage</h1>
                        <p class="fade-in">Explore the ancient temples, monuments, and cultural treasures of India</p>
                    </div>
                </div>
            </div>
            <div class="slide" style="background-image: url('images/slide2.jpg');">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h1 class="fade-in">Experience Vibrant Culture</h1>
                        <p class="fade-in">Immerse yourself in the diverse traditions and festivals of India</p>
                    </div>
                </div>
            </div>
            <div class="slide" style="background-image: url('images/slide3.jpg');">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h1 class="fade-in">Journey Through History</h1>
                        <p class="fade-in">Walk through the pages of India's glorious past</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-nav">
            <div class="slider-dot active"></div>
            <div class="slider-dot"></div>
            <div class="slider-dot"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                // Hide all slides
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                // Show current slide
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                
                // Reset fade-in animation for content
                const contents = slides[index].querySelectorAll('.slide-content > *');
                contents.forEach(content => {
                    content.classList.remove('fade-in');
                    void content.offsetWidth; // Trigger reflow
                    content.classList.add('fade-in');
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            // Start automatic sliding
            function startSlider() {
                slideInterval = setInterval(nextSlide, 5000);
            }

            // Stop automatic sliding
            function stopSlider() {
                clearInterval(slideInterval);
            }

            // Add click event to dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                    stopSlider();
                    startSlider();
                });
            });

            // Start the slider
            startSlider();

            // Pause on hover
            const sliderContainer = document.querySelector('.slider-container');
            sliderContainer.addEventListener('mouseenter', stopSlider);
            sliderContainer.addEventListener('mouseleave', startSlider);
        });
    </script>
</body>
</html> 