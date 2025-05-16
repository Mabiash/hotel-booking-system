<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if order ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: user-bookings.php');
    exit();
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Get order details
$order = null;
$orderItems = [];

$query = "SELECT o.*, b.reference_number, r.name as room_name 
          FROM food_orders o 
          LEFT JOIN bookings b ON o.booking_id = b.id 
          LEFT JOIN rooms r ON b.room_id = r.id 
          WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Order not found or doesn't belong to user
    header('Location: user-bookings.php');
    exit();
}

$order = $result->fetch_assoc();

// Get order items
$query = "SELECT oi.*, f.name as food_name, f.image_url  
          FROM food_order_items oi 
          JOIN food_items f ON oi.food_item_id = f.id 
          WHERE oi.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orderItems[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Order Confirmation</h1>
                <p>Thank you for your order!</p>
            </div>
        </section>

        <section class="order-confirmation">
            <div class="container">
                <div class="confirmation-card">
                    <div class="confirmation-header">
                        <h2>Order #<?php echo $order_id; ?> Confirmed</h2>
                        <p class="confirmation-date">Placed on <?php echo formatDateTime($order['order_date']); ?></p>
                    </div>
                    
                    <div class="confirmation-details">
                        <div class="detail-group">
                            <h3>Status</h3>
                            <p class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></p>
                        </div>
                        
                        <?php if ($order['booking_id']): ?>
                        <div class="detail-group">
                            <h3>Room</h3>
                            <p><?php echo $order['room_name']; ?> (Booking #<?php echo $order['reference_number']; ?>)</p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="detail-group">
                            <h3>Total</h3>
                            <p class="price">$<?php echo number_format($order['total_price'], 2); ?></p>
                        </div>
                    </div>
                    
                    <div class="order-items-list">
                        <h3>Order Items</h3>
                        
                        <?php foreach ($orderItems as $item): ?>
                        <div class="order-item">
                            <div class="item-image">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['food_name']; ?>">
                            </div>
                            <div class="item-details">
                                <h4><?php echo $item['food_name']; ?></h4>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <p class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="confirmation-notes">
                        <?php if ($order['notes']): ?>
                        <h3>Special Instructions</h3>
                        <p><?php echo $order['notes']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="confirmation-actions">
                        <a href="user-bookings.php" class="btn btn-outline">View All Orders</a>
                        <a href="food-menu.php" class="btn btn-primary">Order More Food</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>