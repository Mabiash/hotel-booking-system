/**
 * 88 HOTEL Booking JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Date constraints for booking
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const guestsSelect = document.getElementById('guests');
    
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
            
            updateAvailableRooms();
        });
        
        checkOutInput.addEventListener('change', function() {
            updateAvailableRooms();
        });
        
        if (guestsSelect) {
            guestsSelect.addEventListener('change', function() {
                updateAvailableRooms();
            });
        }
    }
    
    // Function to update available rooms based on selection
    function updateAvailableRooms() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const guests = guestsSelect ? guestsSelect.value : 1;
        
        if (checkIn && checkOut) {
            // Redirect to the booking page with the selected parameters
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('check_in', checkIn);
            currentUrl.searchParams.set('check_out', checkOut);
            if (guests) {
                currentUrl.searchParams.set('guests', guests);
            }
            
            // Only reload if we're already on the booking page
            // to prevent unnecessary refreshes on other pages
            if (window.location.pathname.includes('booking.php')) {
                window.location.href = currentUrl.toString();
            }
        }
    }
    
    // Calculate total price when selecting dates
    const pricePerNightElements = document.querySelectorAll('.room-price');
    const roomPrices = {};
    
    pricePerNightElements.forEach(element => {
        const roomCard = element.closest('.room-card');
        if (roomCard) {
            const roomId = roomCard.getAttribute('data-room-id');
            const priceText = element.textContent;
            const price = parseFloat(priceText.replace(/[^0-9.]/g, ''));
            if (roomId && !isNaN(price)) {
                roomPrices[roomId] = price;
            }
        }
    });
    
    // Calculate nights between two dates
    function calculateNights(checkInDate, checkOutDate) {
        const checkIn = new Date(checkInDate);
        const checkOut = new Date(checkOutDate);
        const timeDiff = checkOut.getTime() - checkIn.getTime();
        const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        return nights > 0 ? nights : 1;
    }
    
    // Update total prices on load and when dates change
    function updateTotalPrices() {
        if (checkInInput && checkOutInput && checkInInput.value && checkOutInput.value) {
            const nights = calculateNights(checkInInput.value, checkOutInput.value);
            const totalPriceElements = document.querySelectorAll('.room-total-price');
            
            totalPriceElements.forEach(element => {
                const roomCard = element.closest('.room-card');
                if (roomCard) {
                    const roomId = roomCard.getAttribute('data-room-id');
                    if (roomId && roomPrices[roomId]) {
                        const totalPrice = roomPrices[roomId] * nights;
                        element.textContent = `$${totalPrice.toFixed(2)} total for ${nights} night${nights > 1 ? 's' : ''}`;
                    }
                }
            });
        }
    }
    
    if (checkInInput && checkOutInput) {
        checkInInput.addEventListener('change', updateTotalPrices);
        checkOutInput.addEventListener('change', updateTotalPrices);
        updateTotalPrices(); // Call on page load
    }
    
    // Room filters
    const filterButtons = document.querySelectorAll('.room-filter');
    const roomCards = document.querySelectorAll('.room-card');
    
    if (filterButtons.length > 0 && roomCards.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active class on buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter rooms
                roomCards.forEach(card => {
                    if (filter === 'all') {
                        card.style.display = 'block';
                    } else {
                        const roomType = card.getAttribute('data-type');
                        card.style.display = roomType === filter ? 'block' : 'none';
                    }
                });
                
                // If no rooms match the filter, show message
                const visibleRooms = document.querySelectorAll('.room-card[style="display: block;"]');
                const noRoomsMessage = document.querySelector('.no-rooms-message');
                
                if (visibleRooms.length === 0 && noRoomsMessage) {
                    noRoomsMessage.style.display = 'block';
                } else if (noRoomsMessage) {
                    noRoomsMessage.style.display = 'none';
                }
            });
        });
    }
    
    // Room image gallery
    const roomImages = document.querySelectorAll('.room-gallery-thumb');
    const mainRoomImage = document.querySelector('.room-main-image img');
    
    if (roomImages.length > 0 && mainRoomImage) {
        roomImages.forEach(image => {
            image.addEventListener('click', function() {
                const imgSrc = this.getAttribute('src');
                
                // Update main image
                mainRoomImage.src = imgSrc;
                
                // Update active class
                roomImages.forEach(img => img.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
});