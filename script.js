// Hillway Modern Website JavaScript

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // IMAGE CAROUSEL
    // ============================================
    const images = document.querySelectorAll('.carousel img');
    let currentIndex = 0;

    function changeImage() {
        if (images.length > 0) {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }
    }

    // Change image every 5 seconds
    if (images.length > 0) {
        setInterval(changeImage, 5000);
    }

    // ============================================
    // ROTATING HERO TEXT
    // ============================================
    const textElement = document.getElementById('rotating-text');
    const texts = [
        '<span style="font-size:8rem; text-transform:uppercase;">HILLWAY</span>', 
        '<span style="font-size:3.5rem; line-height:1.3;">Redefining Real Estate<br>Through Digital<br>Innovation</span>'
    ];
    let textIndex = 0;

    function changeText() {
        if (textElement) {
            textIndex = (textIndex + 1) % texts.length;
            textElement.innerHTML = texts[textIndex];
        }
    }

    // Change text every 5 seconds
    if (textElement) {
        setInterval(changeText, 5000);
    }

    // ============================================
    // SMOOTH SCROLLING
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            
            if (target) {
                const navHeight = document.getElementById('navbar') ? document.getElementById('navbar').offsetHeight : 0;
                const targetPosition = target.offsetTop - navHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }

    // ============================================
    // NAVBAR SCROLL EFFECTS
    // ============================================
    let lastScroll = 0;
    
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        const currentScroll = window.pageYOffset;
        
        if (navbar) {
            // Add scrolled class for styling
            if (currentScroll > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        // Update active nav link based on scroll position
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (currentScroll >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            if (href && href.substring(1) === current) {
                link.classList.add('active');
            }
        });
        
        lastScroll = currentScroll;
    });

    // ============================================
    // FADE IN ANIMATIONS ON SCROLL
    // ============================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all elements with fade-in class
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });

    // ============================================
    // FORM HANDLING
    // ============================================
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        // Add loading state to form submission
        contactForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-submit');
            if (submitBtn) {
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.7';
            }
        });

        // Handle form validation
        const requiredFields = contactForm.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.style.borderColor = '#ff4444';
                } else {
                    this.style.borderColor = '';
                }
            });
        });

        // Handle form errors from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        
        if (error) {
            let errorMessage = '';
            switch(error) {
                case 'missing_fields':
                    errorMessage = 'Please fill in all required fields.';
                    break;
                case 'invalid_email':
                    errorMessage = 'Please enter a valid email address.';
                    break;
                case 'send_failed':
                    errorMessage = 'Message failed to send. Please try again or call us directly on 0333 404 0861.';
                    break;
                default:
                    errorMessage = 'An error occurred. Please try again.';
            }
            
            if (errorMessage) {
                // Create error alert
                const alert = document.createElement('div');
                alert.className = 'alert-error';
                alert.textContent = errorMessage;
                alert.style.cssText = `
                    background: #ff4444;
                    color: white;
                    padding: 1rem;
                    margin-bottom: 1rem;
                    border-radius: 5px;
                    animation: fadeInUp 0.3s ease;
                `;
                contactForm.insertBefore(alert, contactForm.firstChild);
                
                // Remove error from URL
                window.history.replaceState({}, document.title, window.location.pathname);
                
                // Auto-remove alert after 5 seconds
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            }
        }
    }

    // ============================================
    // PARALLAX EFFECT (SUBTLE)
    // ============================================
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero');
        
        if (hero && scrolled < window.innerHeight) {
            // Subtle parallax on hero section
            hero.style.transform = `translateY(${scrolled * 0.3}px)`;
        }
    });

    // ============================================
    // PRELOAD IMAGES
    // ============================================
    function preloadImages() {
        const imageUrls = [
            'images/cityscape1.jpg',
            'images/cityscape2.jpg',
            'images/cityscape3.jpg',
            'images/cityscape4.jpg',
            'images/cityscape5.jpg',
            'images/cityscape6.jpg',
            'images/cityscape7.jpg'
        ];
        
        imageUrls.forEach(url => {
            const img = new Image();
            img.src = url;
        });
    }

    // Preload images after page loads
    window.addEventListener('load', preloadImages);

    // ============================================
    // PERFORMANCE MONITORING
    // ============================================
    // Log page load time for monitoring
    window.addEventListener('load', () => {
        const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
        console.log(`Page loaded in ${loadTime}ms`);
    });

    // ============================================
    // KEYBOARD NAVIGATION
    // ============================================
    document.addEventListener('keydown', (e) => {
        // Press ESC to close mobile menu
        if (e.key === 'Escape' && navMenu && navMenu.classList.contains('active')) {
            navMenu.classList.remove('active');
        }
    });

    // ============================================
    // ENHANCE LINK ACCESSIBILITY
    // ============================================
    // Add title attributes to external links
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        if (!link.getAttribute('title')) {
            link.setAttribute('title', 'Opens in a new window');
        }
    });

    // ============================================
    // SMOOTH REVEAL FOR STATS
    // ============================================
    const stats = document.querySelectorAll('.stat-number');
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateValue(entry.target);
            }
        });
    }, { threshold: 0.5 });

    stats.forEach(stat => {
        statsObserver.observe(stat);
    });

    function animateValue(element) {
        const text = element.textContent;
        const hasPlus = text.includes('+');
        const hasM = text.includes('M');
        let value = parseFloat(text.replace(/[^0-9.]/g, ''));
        
        if (!isNaN(value)) {
            const increment = value / 50;
            let current = 0;
            const timer = setInterval(() => {
                current += increment;
                if (current >= value) {
                    current = value;
                    clearInterval(timer);
                }
                
                let display = Math.floor(current);
                if (hasM) {
                    display = (current).toFixed(2) + 'M';
                } else if (hasPlus) {
                    display = Math.floor(current) + '+';
                }
                element.textContent = display;
            }, 30);
        }
    }

    // ============================================
    // TOUCH EVENTS FOR MOBILE
    // ============================================
    let touchStartX = 0;
    let touchEndX = 0;

    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        heroSection.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        heroSection.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
    }

    function handleSwipe() {
        if (touchEndX < touchStartX - 50) {
            // Swiped left - advance carousel
            changeImage();
        }
        if (touchEndX > touchStartX + 50) {
            // Swiped right - go back in carousel (optional)
            // You could implement reverse carousel here
        }
    }

});

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}