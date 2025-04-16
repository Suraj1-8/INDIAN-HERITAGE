document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Category Filter with Animation
    const filterButtons = document.querySelectorAll('.category-filter button');
    const cultureCards = document.querySelectorAll('.culture-card');
    let activeFilter = 'all';

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.getAttribute('data-category');
            activeFilter = category;
            
            // Enhanced button animation
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.style.transform = 'scale(1)';
            });
            button.classList.add('active');
            button.style.transform = 'scale(1.1)';
            
            // Smooth card filtering with staggered animation
            cultureCards.forEach((card, index) => {
                const delay = index * 50;
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    setTimeout(() => {
                        card.style.display = 'block';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                    }, delay);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px) scale(0.95)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Enhanced Culture Card Interactions
    cultureCards.forEach(card => {
        const img = card.querySelector('img');
        const info = card.querySelector('.culture-info');
        const exploreBtn = card.querySelector('.explore-btn');
        
        // Card hover effect with 3D transform
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) rotateX(5deg)';
            img.style.transform = 'scale(1.1) rotate(2deg)';
            info.style.transform = 'translateY(0)';
            info.style.opacity = '1';
            exploreBtn.style.transform = 'translateY(0)';
            exploreBtn.style.opacity = '1';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) rotateX(0)';
            img.style.transform = 'scale(1) rotate(0)';
            info.style.transform = 'translateY(20px)';
            info.style.opacity = '0';
            exploreBtn.style.transform = 'translateY(20px)';
            exploreBtn.style.opacity = '0';
        });

        // Enhanced explore button interaction
        exploreBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const cardData = {
                title: card.querySelector('h3').textContent,
                category: card.getAttribute('data-category'),
                image: img.src
            };
            
            // Store card data for detail page
            localStorage.setItem('selectedCulture', JSON.stringify(cardData));
            
            // Animate button click
            exploreBtn.style.transform = 'scale(0.95)';
            setTimeout(() => {
                exploreBtn.style.transform = 'scale(1)';
                window.location.href = 'culture-detail.html';
            }, 200);
        });
    });

    // Enhanced Search Functionality
    const searchInput = document.querySelector('.search-input');
    const searchButton = document.querySelector('.search-button');
    let searchTimeout;

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        if (searchTerm.length < 2) {
            // Reset to current filter if search term is too short
            filterButtons.forEach(btn => {
                if (btn.classList.contains('active')) {
                    btn.click();
                }
            });
            return;
        }

        cultureCards.forEach((card, index) => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            const category = card.getAttribute('data-category');
            
            const matches = title.includes(searchTerm) || 
                          description.includes(searchTerm) || 
                          category.includes(searchTerm);
            
            const delay = index * 50;
            
            if (matches) {
                setTimeout(() => {
                    card.style.display = 'block';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                }, delay);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px) scale(0.95)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }

    // Debounced search with loading indicator
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        searchTimeout = setTimeout(() => {
            performSearch();
            searchButton.innerHTML = '<i class="fas fa-search"></i>';
        }, 300);
    });

    searchButton.addEventListener('click', () => {
        performSearch();
    });

    // Enhanced Mobile Menu
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenuBtn.classList.toggle('active');
        navLinks.classList.toggle('active');
        
        // Animate menu items
        const menuItems = navLinks.querySelectorAll('li');
        menuItems.forEach((item, index) => {
            item.style.transitionDelay = `${index * 100}ms`;
            item.style.opacity = navLinks.classList.contains('active') ? '1' : '0';
            item.style.transform = navLinks.classList.contains('active') ? 
                'translateX(0)' : 'translateX(-20px)';
        });
    });

    // Enhanced Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Enhanced Ripple Effect
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${e.clientX - rect.left - size/2}px`;
            ripple.style.top = `${e.clientY - rect.top - size/2}px`;
            
            button.appendChild(ripple);
            
            // Remove ripple after animation
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Enhanced Scroll Animations
    const sections = document.querySelectorAll('.culture-section');
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                
                // Animate child elements with staggered delay
                const children = entry.target.children;
                Array.from(children).forEach((child, index) => {
                    child.style.transitionDelay = `${index * 100}ms`;
                    child.style.opacity = '1';
                    child.style.transform = 'translateY(0)';
                });
            }
        });
    }, observerOptions);

    sections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'all 0.5s ease';
        
        // Initialize child elements
        const children = section.children;
        Array.from(children).forEach(child => {
            child.style.opacity = '0';
            child.style.transform = 'translateY(20px)';
            child.style.transition = 'all 0.5s ease';
        });
        
        observer.observe(section);
    });

    // Add keyboard navigation support
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && navLinks.classList.contains('active')) {
            mobileMenuBtn.click();
        }
    });
}); 