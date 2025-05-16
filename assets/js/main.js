/**
 * 88 HOTEL Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');
    
    if (mobileMenuToggle && mobileMenu && mobileMenuClose) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        mobileMenuClose.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    // User dropdown
    const userDropdownToggle = document.querySelector('.dropdown-toggle');
    const userDropdownMenu = document.querySelector('.dropdown-menu');
    
    if (userDropdownToggle && userDropdownMenu) {
        userDropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdownToggle.classList.toggle('active');
            userDropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function() {
            if (userDropdownMenu.classList.contains('show')) {
                userDropdownToggle.classList.remove('active');
                userDropdownMenu.classList.remove('show');
            }
        });
    }
    
    // Header scroll effect
    const header = document.querySelector('.main-header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    // Testimonials slider
    const testimonialItems = document.querySelectorAll('.testimonial');
    let currentTestimonial = 0;
    
    if (testimonialItems.length > 1) {
        // Initially hide all but the first
        for (let i = 1; i < testimonialItems.length; i++) {
            testimonialItems[i].style.display = 'none';
        }
        
        // Set up automatic rotation
        setInterval(function() {
            testimonialItems[currentTestimonial].style.display = 'none';
            currentTestimonial = (currentTestimonial + 1) % testimonialItems.length;
            testimonialItems[currentTestimonial].style.display = 'block';
            testimonialItems[currentTestimonial].classList.add('fade-in');
        }, 5000);
    }
    
    // Room datepicker constraints
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    
    if (checkInInput && checkOutInput) {
        const today = new Date().toISOString().split('T')[0];
        checkInInput.setAttribute('min', today);
        
        checkInInput.addEventListener('change', function() {
            // Set checkout min date to be at least one day after checkin
            const selectedDate = new Date(this.value);
            selectedDate.setDate(selectedDate.getDate() + 1);
            const nextDay = selectedDate.toISOString().split('T')[0];
            checkOutInput.setAttribute('min', nextDay);
            
            // If checkout is before checkin, update it
            if (checkOutInput.value && checkOutInput.value <= this.value) {
                checkOutInput.value = nextDay;
            }
        });
    }
    
    // Scroll to top button
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    
    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });
        
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('invalid');
                    
                    // Show error message
                    let errorMessage = field.getAttribute('data-error') || 'This field is required';
                    let errorElement = field.parentElement.querySelector('.error-message');
                    
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.className = 'error-message';
                        field.parentElement.appendChild(errorElement);
                    }
                    
                    errorElement.textContent = errorMessage;
                } else {
                    field.classList.remove('invalid');
                    const errorElement = field.parentElement.querySelector('.error-message');
                    if (errorElement) {
                        errorElement.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    // Animate elements on scroll
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    if (animateElements.length > 0) {
        const checkVisibility = function() {
            animateElements.forEach(function(element) {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.classList.add('visible');
                }
            });
        };
        
        window.addEventListener('scroll', checkVisibility);
        checkVisibility(); // Check initially
    }
    
    // Image gallery lightbox
    const galleryImages = document.querySelectorAll('.gallery-image');
    
    if (galleryImages.length > 0) {
        galleryImages.forEach(function(image) {
            image.addEventListener('click', function() {
                const imgSrc = this.getAttribute('src');
                const lightbox = document.createElement('div');
                lightbox.className = 'lightbox';
                lightbox.innerHTML = `
                    <div class="lightbox-content">
                        <img src="${imgSrc}" alt="Gallery Image">
                        <button class="lightbox-close">&times;</button>
                    </div>
                `;
                
                document.body.appendChild(lightbox);
                document.body.style.overflow = 'hidden';
                
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
                        lightbox.remove();
                        document.body.style.overflow = '';
                    }
                });
            });
        });
    }
});