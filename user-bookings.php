<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user's room bookings
$roomBookings = getUserRoomBookings($conn, $_SESSION['user_id']);

// Get user's food orders
$foodOrders = getUserFoodOrders($conn, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>My Bookings</h1>
                <p>View and manage your reservations</p>
            </div>
        </section>

        <section class="user-bookings">
            <div class="container">
                <div class="tabs">
                    <button class="tab-btn active" data-target="room-bookings">Room Bookings</button>
                    <button class="tab-btn" data-target="food-orders">Food Orders</button>
                </div>

                <div class="tab-content active" id="room-bookings">
                    <h2 class="section-title">Room Bookings</h2>
                    
                    <?php if (empty($roomBookings)): ?>
                    <div class="no-bookings-message">
                        <p>You have no room bookings yet.</p>
                        <a href="booking.php" class="btn btn-primary">Book a Room</a>
                    </div>
                    <?php else: ?>
                    <div class="bookings-table-container">
                        <table class="bookings-table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Guests</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roomBookings as $booking): ?>
                                <tr>
                                    <td>#<?php echo $booking['id']; ?></td>
                                    <td><?php echo $booking['room_name']; ?></td>
                                    <td><?php echo formatDate($booking['check_in_date']); ?></td>
                                    <td><?php echo formatDate($booking['check_out_date']); ?></td>
                                    <td><?php echo $booking['guests']; ?></td>
                                    <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower($booking['status']); ?>"><?php echo $booking['status']; ?></span></td>
                                    <td>
                                        <a href="booking-details.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline">View</a>
                                        <?php if ($booking['status'] == 'Confirmed' && strtotime($booking['check_in_date']) > strtotime('+24 hours')): ?>
                                        <a href="cancel-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger cancel-booking">Cancel</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="tab-content" id="food-orders">
                    <h2 class="section-title">Food Orders</h2>
                    
                    <?php if (empty($foodOrders)): ?>
                    <div class="no-orders-message">
                        <p>You have no food orders yet.</p>
                        <a href="food-menu.php" class="btn btn-primary">Order Food</a>
                    </div>
                    <?php else: ?>
                    <div class="orders-table-container">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($foodOrders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo formatDateTime($order['order_date']); ?></td>
                                    <td><?php echo $order['item_count']; ?> items</td>
                                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span></td>
                                    <td>
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/user-bookings.js"></script>
</body>
</html>