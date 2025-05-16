<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Process food item deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $food_id = (int)$_GET['delete'];
    deleteFoodItem($conn, $food_id);
    $_SESSION['success_msg'] = 'Food item deleted successfully';
    header('Location: manage-food.php');
    exit();
}

// Get all food items
$foodItems = getAllFoodItems($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Food Items - 88 HOTEL Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <?php include_once 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include_once 'includes/header.php'; ?>
            
            <main class="admin-main">
                <div class="page-header">
                    <h1>Manage Food Items</h1>
                    <a href="add-food.php" class="btn btn-primary">Add New Food Item</a>
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
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($foodItems as $item): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td>
                                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" class="table-image">
                                </td>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['category_name']; ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $item['is_available'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $item['is_available'] ? 'Available' : 'Unavailable'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-food.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="manage-food.php?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger delete-confirm" data-name="<?php echo $item['name']; ?>">Delete</a>
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