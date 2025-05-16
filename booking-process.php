<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'booking.php';
    header('Location: login.php');
    exit();
}

// Check if required parameters are provided
if (!isset($_GET['room_id']) || !isset($_GET['check_in']) || !isset($_GET['check_out']) || !isset($_GET['guests'])) {
    header('Location: booking.php');
    exit();
}

$room_id = (int)$_GET['room_id'];
$check_in_date = $_GET['check_in'];
$check_out_date = $_GET['check_out'];
$guests = (int)$_GET['guests'];

// Validate dates
$today = date('Y-m-d');
if (strtotime($check_in_date) < strtotime($today) || strtotime($check_out_date) <= strtotime($check_in_date)) {
    $_SESSION['error_msg'] = 'Invalid dates selected';
    header('Location: booking.php');
    exit();
}

// Get room details
$room = getRoomById($conn, $room_id);

if (!$room || $room['status'] != 'Available') {
    $_SESSION['error_msg'] = 'The selected room is not available';
    header('Location: booking.php');
    exit();
}

// Check if room is actually available for the selected dates
$available_rooms = getAvailableRooms($conn, $check_in_date, $check_out_date);
$is_available = false;

foreach ($available_rooms as $available_room) {
    if ($available_room['id'] == $room_id) {
        $is_available = true;
        break;
    }
}

if (!$is_available) {
    $_SESSION['error_msg'] = 'The selected room is not available for the chosen dates';
    header('Location: booking.php');
    exit();
}

// Check if guest count is valid for the room
if ($guests > $room['max_occupancy']) {
    $_SESSION['error_msg'] = 'The selected room can only accommodate up to ' . $room['max_occupancy'] . ' guests';
    header('Location: booking.php');
    exit();
}

// Calculate number of nights and total price
$nights = calculateNights($check_in_date, $check_out_date);
$total_price = $room['price_per_night'] * $nights;

// Process booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $special_requests = isset($_POST['special_requests']) ? trim($_POST['special_requests']) : '';
    $reference_number = generateBookingReference();
    $user_id = $_SESSION['user_id'];
    
    $query = "INSERT INTO bookings (reference_number, user_id, room_id, check_in_date, check_out_date, guests, total_price, special_requests, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed')";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siissids", $reference_number, $user_id, $room_id, $check_in_date, $check_out_date, $guests, $total_price, $special_requests);
    
    if ($stmt->execute()) {
        $booking_id = $conn->insert_id;
        $_SESSION['success_msg'] = 'Booking confirmed! Your booking reference is ' . $reference_number;
        header('Location: booking-confirmation.php?id=' . $booking_id);
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
    <title>Confirm Booking - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Confirm Your Booking</h1>
                <p>Review your reservation details</p>
            </div>
        </section>

        <section class="booking-confirmation">
            <div class="container">
                <div class="booking-summary">
                    <h2>Booking Summary</h2>
                    
                    <div class="room-preview">
                        <div class="room-image">
                            <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['name']; ?>">
                        </div>
                        <div class="room-info">
                            <h3><?php echo $room['name']; ?></h3>
                            <p class="room-type"><?php echo $room['type']; ?></p>
                            <div class="room-features">
                                <span><i class="icon-bed"></i> <?php echo $room['max_occupancy']; ?> Guests</span>
                                <span><i class="icon-size"></i> <?php echo $room['size']; ?> m²</span>
                                <span><i class="icon-view"></i> <?php echo $room['view']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-group">
                            <h4>Check-in Date</h4>
                            <p><?php echo formatDate($check_in_date); ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h4>Check-out Date</h4>
                            <p><?php echo formatDate($check_out_date); ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h4>Guests</h4>
                            <p><?php echo $guests; ?> person<?php echo $guests > 1 ? 's' : ''; ?></p>
                        </div>
                        
                        <div class="detail-group">
                            <h4>Length of Stay</h4>
                            <p><?php echo $nights; ?> night<?php echo $nights > 1 ? 's' : ''; ?></p>
                        </div>
                    </div>
                    
                    <div class="price-breakdown">
                        <h3>Price Breakdown</h3>
                        
                        <div class="price-row">
                            <span>Room Charge</span>
                            <span>$<?php echo number_format($room['price_per_night'], 2); ?> × <?php echo $nights; ?> nights</span>
                        </div>
                        
                        <div class="price-row total">
                            <span>Total</span>
                            <span>$<?php echo number_format($total_price, 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="booking-form">
                    <h2>Complete Your Booking</h2>
                    
                    <form action="booking-process.php?room_id=<?php echo $room_id; ?>&check_in=<?php echo $check_in_date; ?>&check_out=<?php echo $check_out_date; ?>&guests=<?php echo $guests; ?>" method="POST">
                        <div class="form-group">
                            <label for="guest_name">Guest Name</label>
                            <input type="text" id="guest_name" value="<?php echo $_SESSION['user_name']; ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="special_requests">Special Requests (Optional)</label>
                            <textarea id="special_requests" name="special_requests" rows="4" placeholder="Please let us know if you have any special requests or requirements for your stay"></textarea>
                        </div>
                        
                        <div class="form-group terms-checkbox">
                            <label class="checkbox-container">
                                <input type="checkbox" name="agree_terms" required>
                                <span class="checkmark"></span>
                                I agree to the <a href="terms.php" target="_blank">Terms & Conditions</a> and <a href="privacy.php" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <div class="booking-actions">
                            <a href="booking.php" class="btn btn-outline">Back to Search</a>
                            <button type="submit" name="confirm_booking" class="btn btn-primary">Confirm Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/booking.js"></script>
</body>
</html>