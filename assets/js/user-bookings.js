/**
 * 88 HOTEL User Bookings JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Tabs functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    if (tabButtons.length > 0 && tabContents.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                
                // Update active button
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Update active content
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.id === target) {
                        content.classList.add('active');
                    }
                });
            });
        });
    }
    
    // Booking cancellation confirmation
    const cancelButtons = document.querySelectorAll('.cancel-booking');
    
    if (cancelButtons.length > 0) {
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });
    }
    
    // Sort and filter bookings
    const sortButtons = document.querySelectorAll('.sort-bookings');
    const bookingRows = document.querySelectorAll('.bookings-table tbody tr');
    
    if (sortButtons.length > 0 && bookingRows.length > 0) {
        sortButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sortBy = this.getAttribute('data-sort');
                const sortOrder = this.getAttribute('data-order') || 'asc';
                
                // Update sort order for next click
                this.setAttribute('data-order', sortOrder === 'asc' ? 'desc' : 'asc');
                
                // Update sort indicators
                sortButtons.forEach(btn => btn.classList.remove('sorted-asc', 'sorted-desc'));
                this.classList.add(sortOrder === 'asc' ? 'sorted-asc' : 'sorted-desc');
                
                // Sort the rows
                const sortedRows = Array.from(bookingRows).sort((a, b) => {
                    let aValue, bValue;
                    
                    if (sortBy === 'date') {
                        aValue = new Date(a.querySelector('td:nth-child(3)').textContent);
                        bValue = new Date(b.querySelector('td:nth-child(3)').textContent);
                    } else if (sortBy === 'price') {
                        aValue = parseFloat(a.querySelector('td:nth-child(6)').textContent.replace(/[^0-9.]/g, ''));
                        bValue = parseFloat(b.querySelector('td:nth-child(6)').textContent.replace(/[^0-9.]/g, ''));
                    } else {
                        aValue = a.querySelector(`td:nth-child(${parseInt(sortBy)}`).textContent;
                        bValue = b.querySelector(`td:nth-child(${parseInt(sortBy)}`).textContent;
                    }
                    
                    if (sortOrder === 'asc') {
                        return aValue > bValue ? 1 : -1;
                    } else {
                        return aValue < bValue ? 1 : -1;
                    }
                });
                
                // Reinsert sorted rows
                const tbody = document.querySelector('.bookings-table tbody');
                if (tbody) {
                    sortedRows.forEach(row => tbody.appendChild(row));
                }
            });
        });
    }
    
    // Filter bookings by status
    const statusFilter = document.getElementById('status-filter');
    
    if (statusFilter && bookingRows.length > 0) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value.toLowerCase();
            
            bookingRows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(7) .status-badge');
                
                if (statusCell) {
                    const rowStatus = statusCell.textContent.toLowerCase();
                    
                    if (selectedStatus === 'all' || rowStatus === selectedStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
            
            // Show no results message if needed
            const visibleRows = document.querySelectorAll('.bookings-table tbody tr[style=""]');
            const noResultsMessage = document.querySelector('.no-filter-results');
            
            if (visibleRows.length === 0 && noResultsMessage) {
                noResultsMessage.style.display = 'block';
            } else if (noResultsMessage) {
                noResultsMessage.style.display = 'none';
            }
        });
    }
    
    // Search bookings
    const bookingSearch = document.getElementById('booking-search');
    
    if (bookingSearch && bookingRows.length > 0) {
        bookingSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            bookingRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show no results message if needed
            const visibleRows = document.querySelectorAll('.bookings-table tbody tr[style=""]');
            const noResultsMessage = document.querySelector('.no-search-results');
            
            if (visibleRows.length === 0 && noResultsMessage) {
                noResultsMessage.style.display = 'block';
            } else if (noResultsMessage) {
                noResultsMessage.style.display = 'none';
            }
        });
    }
});