<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/5e26758ea8.js" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="http://localhost//hotel-book/asset s/css/style.css">
</head>
<body>
    <style>
        i{
            font-size: 20px;
        }
    </style>
<header class="admin-header">
    <div class="admin-header-left">
        <button class="sidebar-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    
    <div class="admin-header-right">
        <div class="admin-notifications">
            <button class="notifications-toggle">
            <i class="fa-solid fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            <div class="notifications-dropdown">
                <div class="notifications-header">
                    <h3>Notifications</h3>
                    <a href="#" class="mark-all-read">Mark all as read</a>
                </div>
                <div class="notifications-body">
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="icon-booking"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">New room booking received</p>
                            <p class="notification-time">5 minutes ago</p>
                        </div>
                    </a>
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="icon-food"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">New food order #1234 received</p>
                            <p class="notification-time">30 minutes ago</p>
                        </div>
                    </a>
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon">
                            <i class="icon-user"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text">New user registration</p>
                            <p class="notification-time">2 hours ago</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="admin-user">
            <button class="user-dropdown-toggle">
                <div class="user-avatar">
                    <img src="https://tse1.mm.bing.net/th?id=OIP.PKlD9uuBX0m4S8cViqXZHAHaHa&pid=Api&P=0&h=180g" alt="Admin">
                </div>
                <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
                <i class="dropdown-icon"></i>
            </button>
            <div class="user-dropdown">
                <a href="../logout.php" class="dropdown-item">
                    <i class="icon-logout"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</header>
</body>
</html>