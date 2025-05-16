<?php
session_start();
include_once '../includes/config.php';
include_once '../includes/functions.php';
// Handle form submissions for Accept, Decline, Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['booking_id'])) {
        $booking_id = (int)$_POST['booking_id'];
        $action = $_POST['action'];

        if ($action === 'accept') {
            $stmt = $conn->prepare("UPDATE bookings SET status = 'Confirmed' WHERE id = ?");
            $stmt->bind_param("i", $booking_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'decline') {
            $stmt = $conn->prepare("UPDATE bookings SET status = 'Cancelled' WHERE id = ?");
            $stmt->bind_param("i", $booking_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->bind_param("i", $booking_id);
            $stmt->execute();
            $stmt->close();
        }
        // Redirect to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch all bookings

$bookings = getAllBookings($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/admin.css">
    <title>Room Booking Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
            margin: 0;
            padding: 20px;
            color: #2d3446;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
            color: #34495e;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
            background: white;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        thead th {
            background-color: #34495e;
            color: white;
            text-align: left;
            padding: 15px 20px;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
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
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        form {
            margin: 0;
        }

        button {
            padding: 6px 14px;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            color: white;
            user-select: none;
        }

        button.accept {
            background-color: #27ae60;
        }

        button.accept:hover {
            background-color: #1e874b;
        }

        button.decline {
            background-color: #e74c3c;
        }

        button.decline:hover {
            background-color: #b93830;
        }

        button.delete {
            background-color: #7f8c8d;
        }

        button.delete:hover {
            background-color: #5c6e70;
        }

        .status {
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            color: white;
            display: inline-block;
            font-size: 0.85rem;
        }

        .status.Confirmed {
            background-color: #27ae60;
        }

        .status.Declined {
            background-color: #e74c3c;
        }

        .status.Pending {
            background-color: #f39c12;
        }

        .status.Cancelled {
            background-color: #7f8c8d;
        }
    </style>
    <script>
        function confirmAction(form, actionName) {
            if (confirm("Are you sure you want to " + actionName + " this booking?")) {
                form.submit();
            }
            return false;
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
                    <h1>Manage Bookings</h1>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reference Number</th>
                            <th>User ID</th>
                            <th>Room ID</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Guests</th>
                            <th>Total Price</th>
                            <th>Special Requests</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($bookings) === 0): ?>
                            <tr>
                                <td colspan="12" style="text-align:center; padding:30px; color:#999;">No bookings found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $b): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($b['id']); ?></td>
                                    <td><?php echo htmlspecialchars($b['reference_number']); ?></td>
                                    <td><?php echo htmlspecialchars($b['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($b['room_id']); ?></td>
                                    <td><?php echo htmlspecialchars($b['check_in_date']); ?></td>
                                    <td><?php echo htmlspecialchars($b['check_out_date']); ?></td>
                                    <td><?php echo htmlspecialchars($b['guests']); ?></td>
                                    <td>$<?php echo number_format((float)$b['total_price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($b['special_requests']); ?></td>
                                    <td><span class="status <?php echo htmlspecialchars($b['status']); ?>"><?php echo htmlspecialchars($b['status']); ?></span></td>
                                    <td><?php echo htmlspecialchars($b['created_at']); ?></td>
                                    <td class="actions">
                                        <?php if ($b['status'] !== 'Confirmed'): ?>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>" />
                                                <input type="hidden" name="action" value="accept" />
                                                <button type="submit" class="accept">Accept</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if ($b['status'] !== 'Declined'): ?>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>" />
                                                <input type="hidden" name="action" value="decline" />
                                                <button type="submit" class="decline">Cancel</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>" />
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