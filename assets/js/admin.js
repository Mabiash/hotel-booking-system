/**
 * 88 HOTEL Admin Dashboard JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-expanded');
            localStorage.setItem('sidebar-expanded', document.body.classList.contains('sidebar-expanded'));
        });
        
        // Load sidebar state from localStorage
        if (localStorage.getItem('sidebar-expanded') === 'true') {
            document.body.classList.add('sidebar-expanded');
        }
    }
    
    // User dropdown
    const userDropdownToggle = document.querySelector('.user-dropdown-toggle');
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (userDropdownToggle && userDropdown) {
        userDropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            
            // Close notifications dropdown if open
            if (notificationsDropdown && notificationsDropdown.classList.contains('show')) {
                notificationsDropdown.classList.remove('show');
            }
        });
    }
    
    // Notifications dropdown
    const notificationsToggle = document.querySelector('.notifications-toggle');
    const notificationsDropdown = document.querySelector('.notifications-dropdown');
    
    if (notificationsToggle && notificationsDropdown) {
        notificationsToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('show');
            
            // Close user dropdown if open
            if (userDropdown && userDropdown.classList.contains('show')) {
                userDropdown.classList.remove('show');
            }
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        if (userDropdown && userDropdown.classList.contains('show')) {
            userDropdown.classList.remove('show');
        }
        
        if (notificationsDropdown && notificationsDropdown.classList.contains('show')) {
            notificationsDropdown.classList.remove('show');
        }
    });
    
    // Mark all notifications as read
    const markAllReadBtn = document.querySelector('.mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(function(notification) {
                notification.classList.remove('unread');
            });
            
            const notificationBadge = document.querySelector('.notification-badge');
            if (notificationBadge) {
                notificationBadge.textContent = '0';
                notificationBadge.style.display = 'none';
            }
        });
    }
    
    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const itemName = this.getAttribute('data-name');
            const confirmDelete = confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`);
            
            if (confirmDelete) {
                window.location.href = this.getAttribute('href');
            }
        });
    });
    
    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const previewContainer = this.parentElement.querySelector('.upload-preview');
            
            if (previewContainer) {
                previewContainer.innerHTML = '';
                
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Upload Preview';
                        previewContainer.appendChild(img);
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });
    });
    
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
                    
                    const errorMessage = field.getAttribute('data-error-message') || 'This field is required';
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
                
                // Scroll to first error
                const firstInvalidField = form.querySelector('.invalid');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }
        });
    });
    
    // Initialize DataTables if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.data-table').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    }
    
    // Charts initialization (if Chart.js is available)
    if (typeof Chart !== 'undefined') {
        // Revenue chart
        const revenueChartEl = document.getElementById('revenueChart');
        if (revenueChartEl) {
            const revenueChart = new Chart(revenueChartEl, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue',
                        data: [2500, 3200, 4000, 3800, 4200, 4800, 5200, 5800, 5400, 5900, 6300, 7000],
                        borderColor: '#D4AF37',
                        backgroundColor: 'rgba(212, 175, 55, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Bookings chart
        const bookingsChartEl = document.getElementById('bookingsChart');
        if (bookingsChartEl) {
            const bookingsChart = new Chart(bookingsChartEl, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Room Bookings',
                        data: [65, 82, 91, 87, 94, 102, 110, 120, 115, 125, 132, 145],
                        backgroundColor: '#0F172A'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
});