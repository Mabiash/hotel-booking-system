<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Process room deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $room_id = (int)$_GET['delete'];
    deleteRoom($conn, $room_id);
    $_SESSION['success_msg'] = 'Room deleted successfully';
    header('Location: manage-rooms.php');
    exit();
}

// Get all rooms
$rooms = getAllRooms($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - 88 HOTEL Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <?php include_once 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include_once 'includes/header.php'; ?>
            
            <main class="admin-main">
                <div class="page-header">
                    <h1>Manage Rooms</h1>
                    <a href="add-room.php" class="btn btn-primary">Add New Room</a>
                </div>
                
                <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success_msg'];
                    unset($_SESSION['success_msg']);
                    ?>
                </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_msg'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error_msg'];
                    unset($_SESSION['error_msg']);
                    ?>
                </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Capacity</th>
                                <th>Price/Night</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td><?php echo $room['id']; ?></td>
                                <td>
                                    <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['name']; ?>" class="table-image">
                                </td>
                                <td><?php echo $room['name']; ?></td>
                                <td><?php echo $room['type']; ?></td>
                                <td><?php echo $room['max_occupancy']; ?> guests</td>
                                <td>$<?php echo number_format($room['price_per_night'], 2); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($room['status']); ?>">
                                        <?php echo $room['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-room.php?id=<?php echo $room['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="manage-rooms.php?delete=<?php echo $room['id']; ?>" class="btn btn-sm btn-danger delete-confirm" data-name="<?php echo $room['name']; ?>">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>