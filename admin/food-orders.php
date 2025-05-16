<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/functions.php';

// Handle form submissions for Accept, Cancel, Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['order_id'])) {
        $order_id = (int)$_POST['order_id'];
        $action = $_POST['action'];

        if ($action === 'accept') {
            $stmt = $conn->prepare("UPDATE food_orders SET status = 'Processing' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'cancel') {
            $stmt = $conn->prepare("UPDATE food_orders SET status = 'Cancelled' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM food_orders WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'Done') {
            $stmt = $conn->prepare("UPDATE food_orders SET status = 'Completed' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        }
        // Redirect to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch all food orders

$orders = getAllFoodOrders($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Food Order Management</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #2c3e50;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        thead th {
            background-color: #0F172A;
            color: white;
            padding: 16px 20px;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.05em;
            text-align: left;
        }

        tbody tr {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.15s ease;
        }

        tbody tr:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        tbody td {
            padding: 14px 20px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .status {
            padding: 6px 14px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .status.Pending {
            background-color: #f39c12;
        }

        .status.Cancelled {
            background-color: #e74c3c;
        }

        .status.Completed {
            background-color: #27ae60;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        form {
            margin: 0;
        }

        button {
            padding: 7px 15px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            color: white;
            user-select: none;
            transition: background-color 0.2s ease;
        }

        button.accept {
            background-color: #2980b9;
        }

        button.accept:hover {
            background-color: #1c5985;
        }

        button.cancel {
            background-color: #e67e22;
        }

        button.cancel:hover {
            background-color: #d35400;
        }

        button.delete {
            background-color: #7f8c8d;
        }

        button.delete:hover {
            background-color: #5c6e70;
        }
    </style>
    <script>
        function confirmAction(form, actionName) {
            return confirm("Are you sure you want to " + actionName + " this order?");
        }
    </script>
</head>


<body>
    <div class="admin-layout">
        <?php include_once 'includes/sidebar.php'; ?>

        <div class="admin-content">
            <?php include_once 'includes/header.php'; ?>

            <main class="admin-main">
                <div class="page-header">
                    <h1>Manage Foods Orders</h1>
                </div>
                <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Booking ID</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($orders) === 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center; padding:30px; color:#999;">No orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                        <td><?php echo $order['booking_id'] !== null ? htmlspecialchars($order['booking_id']) : '<em>NULL</em>'; ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><span class="status <?php echo htmlspecialchars($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                        <td>$<?php echo number_format((float)$order['total_price'], 2); ?></td>
                        <td><?php echo $order['notes'] !== null ? htmlspecialchars($order['notes']) : '<em>NULL</em>'; ?></td>
                        <td class="actions">
                            <?php if ($order['status'] === 'Pending'): ?>
                                <form method="post" onsubmit="return confirmAction(this, 'accept');" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>" />
                                    <input type="hidden" name="action" value="accept" />
                                    <button type="submit" class="accept">Processing</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($order['status'] !== 'Completed' && $order['status'] !== 'Cancelled'): ?>
                                <form method="post" onsubmit="return confirmAction(this, 'cancel');" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>" />
                                    <input type="hidden" name="action" value="cancel" />
                                    <button type="submit" class="cancel">Cancel</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($order['status'] !== 'Delete' && $order['status'] !== 'Completed') : ?>
                                <form method="post" onsubmit="return confirmAction(this, 'Done');" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>" />
                                    <input type="hidden" name="action" value="Done" />
                                    <button type="submit" class="cancel">Done</button>
                                </form>
                            <?php endif; ?>
                            <form method="post" onsubmit="return confirmAction(this, 'delete');" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>" />
                                <input type="hidden" name="action" value="delete" />
                                <button type="submit" class="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
              
            </main>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>

</html>

