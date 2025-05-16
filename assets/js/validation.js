/**
 * 88 HOTEL Form Validation JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Form validation for Login form
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Email validation
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value.trim() === '') {
                showError(emailInput, 'Email is required');
                isValid = false;
            } else if (emailInput && !isValidEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            } else if (emailInput) {
                clearError(emailInput);
            }
            
            // Password validation
            const passwordInput = document.getElementById('password');
            if (passwordInput && passwordInput.value.trim() === '') {
                showError(passwordInput, 'Password is required');
                isValid = false;
            } else if (passwordInput) {
                clearError(passwordInput);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Form validation for Registration form
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Name validation
            const nameInput = document.getElementById('name');
            if (nameInput && nameInput.value.trim() === '') {
                showError(nameInput, 'Name is required');
                isValid = false;
            } else if (nameInput) {
                clearError(nameInput);
            }
            
            // Email validation
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value.trim() === '') {
                showError(emailInput, 'Email is required');
                isValid = false;
            } else if (emailInput && !isValidEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            } else if (emailInput) {
                clearError(emailInput);
            }
            
            // Phone validation
            const phoneInput = document.getElementById('phone');
            if (phoneInput && phoneInput.value.trim() === '') {
                showError(phoneInput, 'Phone number is required');
                isValid = false;
            } else if (phoneInput) {
                clearError(phoneInput);
            }
            
            // Password validation
            const passwordInput = document.getElementById('password');
            if (passwordInput && passwordInput.value.trim() === '') {
                showError(passwordInput, 'Password is required');
                isValid = false;
            } else if (passwordInput && passwordInput.value.length < 6) {
                showError(passwordInput, 'Password must be at least 6 characters');
                isValid = false;
            } else if (passwordInput) {
                clearError(passwordInput);
            }
            
            // Confirm password validation
            const confirmPasswordInput = document.getElementById('confirm_password');
            if (confirmPasswordInput && confirmPasswordInput.value.trim() === '') {
                showError(confirmPasswordInput, 'Please confirm your password');
                isValid = false;
            } else if (confirmPasswordInput && passwordInput && confirmPasswordInput.value !== passwordInput.value) {
                showError(confirmPasswordInput, 'Passwords do not match');
                isValid = false;
            } else if (confirmPasswordInput) {
                clearError(confirmPasswordInput);
            }
            
            // Terms agreement validation
            const termsCheckbox = document.querySelector('input[name="agree_terms"]');
            if (termsCheckbox && !termsCheckbox.checked) {
                showError(termsCheckbox, 'You must agree to the Terms of Service and Privacy Policy');
                isValid = false;
            } else if (termsCheckbox) {
                clearError(termsCheckbox);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Form validation for Contact form
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Name validation
            const nameInput = document.getElementById('name');
            if (nameInput && nameInput.value.trim() === '') {
                showError(nameInput, 'Name is required');
                isValid = false;
            } else if (nameInput) {
                clearError(nameInput);
            }
            
            // Email validation
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value.trim() === '') {
                showError(emailInput, 'Email is required');
                isValid = false;
            } else if (emailInput && !isValidEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            } else if (emailInput) {
                clearError(emailInput);
            }
            
            // Subject validation
            const subjectInput = document.getElementById('subject');
            if (subjectInput && subjectInput.value.trim() === '') {
                showError(subjectInput, 'Subject is required');
                isValid = false;
            } else if (subjectInput) {
                clearError(subjectInput);
            }
            
            // Message validation
            const messageInput = document.getElementById('message');
            if (messageInput && messageInput.value.trim() === '') {
                showError(messageInput, 'Message is required');
                isValid = false;
            } else if (messageInput) {
                clearError(messageInput);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Form validation for Booking form
    const bookingForm = document.getElementById('bookingForm');
    
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Check-in date validation
            const checkInInput = document.getElementById('check_in');
            if (checkInInput && checkInInput.value === '') {
                showError(checkInInput, 'Check-in date is required');
                isValid = false;
            } else if (checkInInput) {
                clearError(checkInInput);
            }
            
            // Check-out date validation
            const checkOutInput = document.getElementById('check_out');
            if (checkOutInput && checkOutInput.value === '') {
                showError(checkOutInput, 'Check-out date is required');
                isValid = false;
            } else if (checkOutInput && checkInInput && checkOutInput.value <= checkInInput.value) {
                showError(checkOutInput, 'Check-out date must be after check-in date');
                isValid = false;
            } else if (checkOutInput) {
                clearError(checkOutInput);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Helper functions
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        let errorElement = formGroup.querySelector('.error-message');
        
        input.classList.add('invalid');
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            formGroup.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }
    
    function clearError(input) {
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');
        
        input.classList.remove('invalid');
        
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email.toLowerCase());
    }
    
    // Real-time validation
    const inputFields = document.querySelectorAll('input, textarea');
    
    inputFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.hasAttribute('required') && this.value.trim() === '') {
                const fieldName = this.previousElementSibling ? this.previousElementSibling.textContent : 'This field';
                showError(this, `${fieldName} is required`);
            } else if (this.id === 'email' && this.value.trim() !== '' && !isValidEmail(this.value)) {
                showError(this, 'Please enter a valid email address');
            } else if (this.id === 'password' && this.value.trim() !== '' && this.value.length < 6) {
                showError(this, 'Password must be at least 6 characters');
            } else if (this.id === 'confirm_password' && this.value.trim() !== '') {
                const passwordInput = document.getElementById('password');
                if (passwordInput && this.value !== passwordInput.value) {
                    showError(this, 'Passwords do not match');
                } else {
                    clearError(this);
                }
            } else {
                clearError(this);
            }
        });
    });
});