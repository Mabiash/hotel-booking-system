<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="http://localhost/hotel-book/assets/css/style.css">
</head>
<body>
<header class="main-header">
    <div class="container">
        <div class="header-inner">
            <div class="logo">
                <a href="index.php">
                    <span class="logo-text">88 HOTEL</span>
                </a>
            </div>
            
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rooms.php">Rooms</a></li>
                    <li><a href="booking.php">Book Now</a></li>
                    <li><a href="food-menu.php">Food Menu</a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <button class="dropdown-toggle">
                            <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
                            <i class="dropdown-icon"></i>
                        </button>
                        <div class="dropdown-menu">
                            <?php if ($_SESSION['is_admin'] == 1): ?>
                                <a href="admin/index.php">Admin Dashboard</a>
                            <?php endif; ?>
                            <a href="profile.php">My Profile</a>
                            <a href="user-bookings.php">My Bookings</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="login.php" class="btn btn-secondary">Login</a>
                        <a href="register.php" class="btn btn-primary">Register</a>
                    </div>
                <?php endif; ?>
                
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
</header>
<div class="mobile-menu">
    <div class="mobile-menu-header">
        <div class="logo">
            <span class="logo-text">88 HOTEL</span>
        </div>
        <button class="mobile-menu-close">&times;</button>
    </div>
    <nav class="mobile-nav">
        <ul class="mobile-nav-list">
            <li><a href="index.php">Home</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="booking.php">Book Now</a></li>
            <li><a href="food-menu.php">Food Menu</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['is_admin'] == 1): ?>
                    <li><a href="admin/index.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <li><a href="user-profile.php">My Profile</a></li>
                <li><a href="user-bookings.php">My Bookings</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
</body>
</html>