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
$query = "SELECT b.*, r.name as room_name 
          FROM bookings b 
          JOIN rooms r ON b.room_id = r.id 
          WHERE b.id = ? AND b.user_id = ? AND b.status = 'Confirmed'";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Booking not found, doesn't belong to user, or is not in confirmed status
    $_SESSION['error_msg'] = 'The booking cannot be cancelled';
    header('Location: user-bookings.php');
    exit();
}

$booking = $result->fetch_assoc();

// Check if cancellation is allowed (at least 24 hours before check-in)
$check_in_date = strtotime($booking['check_in_date']);
$cancellation_deadline = strtotime('+24 hours');

if ($check_in_date <= $cancellation_deadline) {
    $_SESSION['error_msg'] = 'Bookings can only be cancelled at least 24 hours before check-in';
    header('Location: user-bookings.php');
    exit();
}

// Process cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_cancellation'])) {
    $query = "UPDATE bookings SET status = 'Cancelled' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $booking_id, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_msg'] = 'Your booking has been cancelled successfully';
        header('Location: user-bookings.php');
        exit();
    } else {
        $_SESSION['error_msg'] = 'An error occurred. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Cancel Booking</h1>
                <p>Please confirm your cancellation request</p>
            </div>
        </section>

        <section class="cancel-booking">
            <div class="container">
                <div class="cancellation-card">
                    <h2>Booking Cancellation</h2>
                    
                    <div class="booking-summary">
                        <p>You are about to cancel the following booking:</p>
                        
                        <div class="detail-group">
                            <h3>Booking Reference</h3>
                            <p>#<?php echo $booking['reference_number']; ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h3>Room</h3>
                            <p><?php echo $booking['room_name']; ?></p>
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
                            <h3>Total Price</h3>
                            <p>$<?php echo number_format($booking['total_price'], 2); ?></p>
                        </div>
                    </div>
                    
                    <div class="cancellation-notice">
                        <h3>Cancellation Policy</h3>
                        <p>Please note the following:</p>
                        <ul>
                            <li>Cancellations made at least 24 hours before check-in are free of charge.</li>
                            <li>Once cancelled, this action cannot be undone.</li>
                            <li>If you wish to stay at our hotel after cancelling, you will need to make a new booking, subject to availability and current rates.</li>
                        </ul>
                    </div>
                    
                    <form action="cancel-booking.php?id=<?php echo $booking_id; ?>" method="POST">
                        <div class="form-group terms-checkbox">
                            <label class="checkbox-container">
                                <input type="checkbox" name="agree_terms" required>
                                <span class="checkmark"></span>
                                I understand and agree to the cancellation policy
                            </label>
                        </div>
                        
                        <div class="cancellation-actions">
                            <a href="user-bookings.php" class="btn btn-secondary">Keep My Booking</a>
                            <button type="submit" name="confirm_cancellation" class="btn btn-danger">Confirm Cancellation</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>