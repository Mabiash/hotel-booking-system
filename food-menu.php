<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';

// Get all food categories
$categories = getFoodCategories($conn);

// Get food items by category or all if no category selected
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$foodItems = getFoodItems($conn, $category_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu - 88 HOTEL</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
    
    <main>
        <section class="page-header">
            <div class="container">
                <h1>Food Menu</h1>
                <p>Explore our exquisite culinary offerings</p>
            </div>
        </section>

        <section class="food-menu">
            <div class="container">
                <div class="category-tabs">
                    <a href="food-menu.php" class="category-tab <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">All</a>
                    <?php foreach ($categories as $category): ?>
                    <a href="food-menu.php?category=<?php echo $category['id']; ?>" class="category-tab <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : ''; ?>">
                        <?php echo $category['name']; ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <div class="food-items-grid">
                    <?php if (empty($foodItems)): ?>
                    <div class="no-items-message">
                        <p>No food items available in this category.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($foodItems as $item): ?>
                    <div class="food-card">
                        <div class="food-image">
                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                        </div>
                        <div class="food-details">
                            <h3><?php echo $item['name']; ?></h3>
                            <p class="food-price">$<?php echo number_format($item['price'], 2); ?></p>
                            <p class="food-description"><?php echo $item['description']; ?></p>
                            <div class="food-actions">
                                <button class="btn btn-primary add-to-cart" data-id="<?php echo $item['id']; ?>" data-name="<?php echo $item['name']; ?>" data-price="<?php echo $item['price']; ?>">
                                    Add to Order
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="current-order" id="currentOrder">
            <div class="container">
                <h2 class="section-title">Your Order</h2>
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="order-items" id="orderItems">
                    <p class="empty-order-message" id="emptyOrderMessage">Your order is empty.</p>
                    <!-- Order items will be inserted here via JavaScript -->
                </div>
                <div class="order-summary" id="orderSummary" style="display: none;">
                    <div class="order-total">
                        <span>Total:</span>
                        <span id="orderTotal">$0.00</span>
                    </div>
                    <button class="btn btn-primary btn-block" id="placeOrder">Place Order</button>
                </div>
                <?php else: ?>
                <div class="order-login-message">
                    <p>Please <a href="login.php">login</a> to place an order.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/food-menu.js"></script>
</body>
</html>