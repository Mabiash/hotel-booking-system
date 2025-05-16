<?php
// User related functions
function getUserById($conn, $user_id) {
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getAllUsers($conn, $except_id = null) {
    $query = "SELECT * FROM users";
    if ($except_id) {
        $query .= " WHERE id != ?";
    }
    $query .= " ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($query);
    if ($except_id) {
        $stmt->bind_param("i", $except_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    return $users;
}

function toggleUserStatus($conn, $user_id) {
    $query = "UPDATE users SET is_active = 1 - is_active WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

// Room related functions
function getAllRooms($conn) {
    $query = "SELECT * FROM rooms ORDER BY id";
    $result = $conn->query($query);
    
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    return $rooms;
}

function getRoomById($conn, $room_id) {
    $query = "SELECT * FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getFeaturedRooms($conn, $limit = 3) {
    $query = "SELECT * FROM rooms WHERE is_featured = 1 AND status = 'Available' ORDER BY price_per_night DESC LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    return $rooms;
}

function getAvailableRooms($conn, $check_in, $check_out) {
    $query = "SELECT * FROM rooms WHERE status = 'Available' AND id NOT IN (
                SELECT room_id FROM bookings 
                WHERE ((check_in_date <= ? AND check_out_date > ?) 
                OR (check_in_date < ? AND check_out_date >= ?) 
                OR (check_in_date >= ? AND check_out_date <= ?))
                AND status != 'Cancelled'
              )";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    
    return $rooms;
}

function deleteRoom($conn, $room_id) {
    $query = "DELETE FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $room_id);
    return $stmt->execute();
}

// Food related functions
function getFoodCategories($conn) {
    $query = "SELECT * FROM food_categories ORDER BY name";
    $result = $conn->query($query);
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

function getFoodItems($conn, $category_id = null) {
    $query = "SELECT f.*, c.name as category_name 
              FROM food_items f 
              JOIN food_categories c ON f.category_id = c.id";
    
    if ($category_id) {
        $query .= " WHERE f.category_id = ?";
    }
    
    $query .= " ORDER BY f.name";
    
    $stmt = $conn->prepare($query);
    if ($category_id) {
        $stmt->bind_param("i", $category_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    return $items;
}

function getAllFoodItems($conn) {
    $query = "SELECT f.*, c.name as category_name 
              FROM food_items f 
              JOIN food_categories c ON f.category_id = c.id
              ORDER BY f.id";
    
    $result = $conn->query($query);
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    return $items;
}

function getFoodItemById($conn, $item_id) {
    $query = "SELECT f.*, c.name as category_name 
              FROM food_items f 
              JOIN food_categories c ON f.category_id = c.id
              WHERE f.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function deleteFoodItem($conn, $item_id) {
    $query = "DELETE FROM food_items WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $item_id);
    return $stmt->execute();
}

// Booking and order functions
function getUserRoomBookings($conn, $user_id) {
    $query = "SELECT b.*, r.name as room_name 
              FROM bookings b 
              JOIN rooms r ON b.room_id = r.id 
              WHERE b.user_id = ? 
              ORDER BY b.created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    return $bookings;
}

function getAllBookings($conn) {
    $query = "SELECT * FROM bookings ORDER BY created_at DESC";
    $result = $conn->query($query);
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    return $bookings;
}


function getAllFoodOrders($conn) {
    $query = "SELECT * FROM food_orders ORDER BY order_date DESC";
    $result = $conn->query($query);
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    return $orders;
}

function getUserFoodOrders($conn, $user_id) {
    $query = "SELECT o.id, o.user_id, o.order_date, o.status, o.total_price,
              COUNT(oi.id) as item_count
              FROM food_orders o
              JOIN food_order_items oi ON o.id = oi.order_id
              WHERE o.user_id = ?
              GROUP BY o.id
              ORDER BY o.order_date DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    
    return $orders;
}

function getRecentBookings($conn, $limit = 5) {
    $query = "SELECT b.id, b.check_in_date, b.status, 
              u.name as user_name, r.name as room_name
              FROM bookings b
              JOIN users u ON b.user_id = u.id
              JOIN rooms r ON b.room_id = r.id
              ORDER BY b.created_at DESC
              LIMIT ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    return $bookings;
}

function getRecentFoodOrders($conn, $limit = 5) {
    $query = "SELECT o.id, o.order_date, o.status, u.name as user_name,
              COUNT(oi.id) as item_count
              FROM food_orders o
              JOIN users u ON o.user_id = u.id
              JOIN food_order_items oi ON o.id = oi.order_id
              GROUP BY o.id
              ORDER BY o.order_date DESC
              LIMIT ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    
    return $orders;
}

// Dashboard statistics
function getDashboardStats($conn) {
    $stats = [];
    
    // Total users
    $query = "SELECT COUNT(*) as count FROM users";
    $result = $conn->query($query);
    $stats['total_users'] = $result->fetch_assoc()['count'];
    
    // Total bookings
    $query = "SELECT COUNT(*) as count FROM bookings";
    $result = $conn->query($query);
    $stats['total_bookings'] = $result->fetch_assoc()['count'];
    
    // Total food orders
    $query = "SELECT COUNT(*) as count FROM food_orders";
    $result = $conn->query($query);
    $stats['total_orders'] = $result->fetch_assoc()['count'];
    
    // Total revenue
    $query = "SELECT SUM(total_price) as total FROM bookings WHERE status != 'Cancelled'";
    $result = $conn->query($query);
    $booking_revenue = $result->fetch_assoc()['total'] ?: 0;
    
    $query = "SELECT SUM(total_price) as total FROM food_orders WHERE status != 'Cancelled'";
    $result = $conn->query($query);
    $food_revenue = $result->fetch_assoc()['total'] ?: 0;
    
    $stats['total_revenue'] = $booking_revenue + $food_revenue;
    
    return $stats;
}

// Helper functions
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('M d, Y h:i A', strtotime($datetime));
}

function calculateNights($check_in, $check_out) {
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $interval = $check_in_date->diff($check_out_date);
    return $interval->days;
}

function generateBookingReference($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $reference = '';
    for ($i = 0; $i < $length; $i++) {
        $reference .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $reference;
}



function uploadImage($file, $destination_path) {
    // Create directory if doesn't exist
    if (!file_exists($destination_path)) {
        mkdir($destination_path, 0777, true);
    }
    
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Generate unique filename
    $new_filename = uniqid() . '.' . $file_ext;
    $target_path = $destination_path . '/' . $new_filename;
    
    // Check file extension
    if (!in_array($file_ext, ALLOWED_EXTENSIONS)) {
        return [
            'success' => false,
            'message' => 'Invalid file extension. Allowed: ' . implode(', ', ALLOWED_EXTENSIONS)
        ];
    }
    
    // Check file size
    if ($file_size > MAX_FILE_SIZE) {
        return [
            'success' => false,
            'message' => 'File is too large. Maximum size: ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB'
        ];
    }
    
    // Move uploaded file
    if (move_uploaded_file($file_tmp, $target_path)) {
        return [
            'success' => true,
            'filename' => $new_filename,
            'path' => $target_path
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to upload file'
        ];
    }
}


?>