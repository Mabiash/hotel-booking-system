<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if booking ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: user-bookings.php');
    exit();
}

$booking_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Get booking details
$query = "SELECT b.*, r.name as room_name, r.image_url, r.type, r.price_per_night 
          FROM bookings b 
          JOIN rooms r ON b.room_id = r.id 
          WHERE b.id = ? AND b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Booking not found or doesn't belong to user
    header('Location: user-bookings.php');
    exit();
}

$booking = $result->fetch_assoc();
$nights = calculateNights($booking['check_in_date'], $booking['check_out_date']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Booking Confirmation</h1>
                <p>Thank you for your reservation!</p>
            </div>
        </section>

        <section class="booking-confirmation">
            <div class="container">
                <div class="confirmation-card">
                    <div class="confirmation-header">
                        <h2>Booking #<?php echo $booking['reference_number']; ?> Confirmed</h2>
                        <p class="confirmation-date">Booked on <?php echo formatDateTime($booking['created_at']); ?></p>
                    </div>
                    
                    <div class="confirmation-details">
                        <div class="detail-group">
                            <h3>Status</h3>
                            <p class="status-badge status-<?php echo strtolower($booking['status']); ?>"><?php echo $booking['status']; ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h3>Check-in</h3>
                            <p><?php echo formatDate($booking['check_in_date']); ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h3>Check-out</h3>
                            <p><?php echo formatDate($booking['check_out_date']); ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h3>Guests</h3>
                            <p><?php echo $booking['guests']; ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h3>Total</h3>
                            <p class="price">$<?php echo number_format($booking['total_price'], 2); ?></p>
                        </div>
                    </div>
                    
                    <div class="room-summary">
                        <div class="room-image">
                            <img src="<?php echo $booking['image_url']; ?>" alt="<?php echo $booking['room_name']; ?>">
                        </div>
                        <div class="room-info">
                            <h3><?php echo $booking['room_name']; ?></h3>
                            <p class="room-type"><?php echo $booking['type']; ?></p>
                            <p class="room-price">$<?php echo number_format($booking['price_per_night'], 2); ?> per night × <?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></p>
                        </div>
                    </div>
                    
                    <?php if ($booking['special_requests']): ?>
                    <div class="special-requests">
                        <h3>Special Requests</h3>
                        <p><?php echo $booking['special_requests']; ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="confirmation-notes">
                        <h3>Important Information</h3>
                        <ul>
                            <li>Check-in time is 3:00 PM. Early check-in is subject to availability.</li>
                            <li>Check-out time is 12:00 PM. Late check-out may incur additional charges.</li>
                            <li>Please present a valid ID and the credit card used for booking upon check-in.</li>
                            <li>For cancellations or modifications, please contact us at least 24 hours before check-in.</li>
                        </ul>
                    </div>
                    
                    <div class="confirmation-actions">
                        <a href="user-bookings.php" class="btn btn-outline">View All Bookings</a>
                        <a href="food-menu.php" class="btn btn-secondary">Order Food</a>
                        <?php if ($booking['status'] == 'Confirmed' && strtotime($booking['check_in_date']) > strtotime('+24 hours')): ?>
                        <a href="cancel-booking.php?id=<?php echo $booking_id; ?>" class="btn btn-danger cancel-booking">Cancel Booking</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
    <script>
        // Booking cancellation confirmation
        const cancelBtn = document.querySelector('.cancel-booking');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                    window.location.href = this.getAttribute('href');
                }
            });
        }
    </script>
</body>
</html>