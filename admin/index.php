<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Get dashboard statistics
$stats = getDashboardStats($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - 88 HOTEL</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <?php include_once 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include_once 'includes/header.php'; ?>
            
            <main class="admin-main">
                <div class="page-header">
                    <h1>Dashboard</h1>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                        <i class="icon-bed sidebar-icon fa-solid fa-user-tie"></i>
                        </div>
                        <div class="stat-details">
                            <p class="stat-title">Total Users</p>
                            <p class="stat-value"><?php echo $stats['total_users']; ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                        <i class="sidebar-icon fa-solid fa-book"></i>
                        </div>
                        <div class="stat-details">
                            <p class="stat-title">Room Bookings</p>
                            <p class="stat-value"><?php echo $stats['total_bookings']; ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                        <i class="sidebar-icon fa-solid fa-bowl-food"></i>
                        </div>
                        <div class="stat-details">
                            <p class="stat-title">Food Orders</p>
                            <p class="stat-value"><?php echo $stats['total_orders']; ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                        <i class="fa-solid fa-money-bill"></i>
                        </div>
                        <div class="stat-details">
                            <p class="stat-title">Revenue</p>
                            <p class="stat-value">$<?php echo number_format($stats['total_revenue'], 2); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2>Recent Bookings</h2>
                            <a href="bookings.php" class="view-all">View All</a>
                        </div>
                        <div class="card-body">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $recentBookings = getRecentBookings($conn, 5);
                                    foreach ($recentBookings as $booking):
                                    ?>
                                    <tr>
                                        <td>#<?php echo $booking['id']; ?></td>
                                        <td><?php echo $booking['user_name']; ?></td>
                                        <td><?php echo $booking['room_name']; ?></td>
                                        <td><?php echo formatDate($booking['check_in_date']); ?></td>
                                        <td><span class="status-badge status-<?php echo strtolower($booking['status']); ?>"><?php echo $booking['status']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2>Recent Food Orders</h2>
                            <a href="food-orders.php" class="view-all">View All</a>
                        </div>
                        <div class="card-body">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $recentOrders = getRecentFoodOrders($conn, 5);
                                    foreach ($recentOrders as $order):
                                    ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo $order['user_name']; ?></td>
                                        <td><?php echo formatDateTime($order['order_date']); ?></td>
                                        <td><?php echo $order['item_count']; ?></td>
                                        <td><span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>