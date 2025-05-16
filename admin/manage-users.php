<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Process user status update
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $user_id = (int)$_GET['toggle'];
    toggleUserStatus($conn, $user_id);
    $_SESSION['success_msg'] = 'User status updated successfully';
    header('Location: manage-users.php');
    exit();
}

// Get all users except current admin
$users = getAllUsers($conn, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - 88 HOTEL Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <?php include_once 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include_once 'includes/header.php'; ?>
            
            <main class="admin-main">
                <div class="page-header">
                    <h1>Manage Users</h1>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['phone']; ?></td>
                                <td><?php echo $user['is_admin'] ? 'Admin' : 'User'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($user['created_at']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                        <a href="manage-users.php?toggle=<?php echo $user['id']; ?>" class="btn btn-sm <?php echo $user['is_active'] ? 'btn-warning' : 'btn-success'; ?>">
                                            <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                        </a>
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