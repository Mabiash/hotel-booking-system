<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get the JSON data from the request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate the data
if (!$data || !isset($data['items']) || empty($data['items']) || !isset($data['total'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();
    
    // Create the food order
    $user_id = $_SESSION['user_id'];
    $total_price = floatval($data['total']);
    
    // Get booking_id if user has an active booking
    $booking_id = null;
    $query = "SELECT id FROM bookings 
              WHERE user_id = ? 
              AND status = 'Confirmed' 
              AND check_in_date <= CURDATE() 
              AND check_out_date >= CURDATE() 
              ORDER BY check_in_date DESC 
              LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $booking_id = $row['id'];
    }
    
    // Insert order into food_orders table
    $query = "INSERT INTO food_orders (user_id, booking_id, total_price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iid", $user_id, $booking_id, $total_price);
    $stmt->execute();
    $order_id = $conn->insert_id;
    
    // Insert order items
    $query = "INSERT INTO food_order_items (order_id, food_item_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    foreach ($data['items'] as $item) {
        $food_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        
        $stmt->bind_param("iiid", $order_id, $food_id, $quantity, $price);
        $stmt->execute();
    }
    
    // Commit transaction
    $conn->commit();
    
    // Return success response
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>