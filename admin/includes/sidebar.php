<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="admin-logo">
            <span class="logo-text">88 HOTEL</span>
            <span class="admin-badge">Admin</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="index.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">

                    <i class="fa-solid fa-table-columns sidebar-icon icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="bookings.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">

                    <i class="sidebar-icon fa-solid fa-book"></i>
                    <span>Room Bookings</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="manage-rooms.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-rooms.php' ? 'active' : ''; ?>">

                    <i class="sidebar-icon fa-solid fa-bars-progress"></i>
                    <span>Manage Rooms</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="food-orders.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'food-orders.php' ? 'active' : ''; ?>">
                    <i class="sidebar-icon fa-solid fa-bowl-food"></i>
                    <span>Food Orders</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="manage-food.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-food.php' ? 'active' : ''; ?>">
                    <i class="sidebar-icon fa-solid fa-wheat-awn-circle-exclamation"></i>
                    <span>Manage Food Items</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="manage-users.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-users.php' ? 'active' : ''; ?>">
                    <i class="sidebar-icon fa-solid fa-user-tie"></i>
                    <span>Manage Users</span>
                </a>
            </li>

        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="../index.php" class="sidebar-footer-link">
            <i class="sidebar-icon icon-home"></i>
            <span>Home</span>
        </a>
        <a href="../logout.php" class="sidebar-footer-link">

            <span>Logout</span>
            <i class="fa-solid fa-right-from-bracket" style="margin-left: 10px;"></i>
        </a>
    </div>
</aside>