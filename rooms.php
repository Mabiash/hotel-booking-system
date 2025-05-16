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

$allRooms = getAllRooms($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Book Your Stay</h1>
                <p>Select from our luxury accommodations</p>
            </div>
        </section>

        <!-- <section class="booking-search">
            <div class="container">
                <form action="booking.php" method="GET" class="search-form">
                    <div class="form-group">
                        <label for="check_in">Check-in Date</label>
                        <input type="date" id="check_in" name="check_in" value="<?php echo $checkInDate; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="check_out">Check-out Date</label>
                        <input type="date" id="check_out" name="check_out" value="<?php echo $checkOutDate; ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="guests">Guests</label>
                        <select id="guests" name="guests">
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo ($guests == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Search Availability</button>
                </form>
            </div>
        </section> -->

        <section class="available-rooms">
            <div class="container">
                <h2 class="section-title">All Rooms</h2>
                
                <?php if (empty($allRooms)): ?>
                <div class="no-rooms-message">
                    <p>No rooms available for the selected dates. Please try different dates.</p>
                </div>
                <?php else: ?>
                <div class="rooms-grid">
                    <?php foreach ($allRooms as $room): ?>
                    <div class="room-card">
                        <div class="room-image">
                            <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['name']; ?>">
                        </div>
                        <div class="room-details">
                            <h3><?php echo $room['name']; ?></h3>
                            <p class="room-price">$<?php echo $room['price_per_night']; ?> <span>per night</span></p>
                            <div class="room-features">
                                <span><i class="icon-bed"></i> <?php echo $room['max_occupancy']; ?> Guests</span>
                                <span><i class="icon-size"></i> <?php echo $room['size']; ?> m²</span>
                                <span><i class="icon-view"></i> <?php echo $room['view']; ?></span>
                            </div>
                            <p class="room-description"><?php echo substr($room['description'], 0, 100); ?>...</p>
                            <div class="room-actions">
                                <a href="room-details.php?id=<?php echo $room['id']; ?>" class="btn btn-outline">View Details</a>
                                <a href="booking-process.php?room_id=<?php echo $room['id']; ?>&check_in=<?php echo $checkInDate; ?>&check_out=<?php echo $checkOutDate; ?>&guests=<?php echo $guests; ?>" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/booking.js"></script>
</body>
</html>