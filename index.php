<?php
session_start();
include_once 'includes/config.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>88 HOTEL - Luxury Accommodations</title>
    <style>
        .hero {
            height: 80vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://media-cdn.tripadvisor.com/media/photo-s/15/de/51/1e/hotel-88-kopo-bandung.jpg') !important;
            background-size: cover !important;
            background-position: center !important;
            color: var(--color-white);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to 88 HOTEL</h1>
                <p>Experience luxury like never before</p>
                <a href="booking.php" class="btn btn-primary">Book Now</a>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2 class="section-title">Our Amenities</h2>
                <div class="features-grid">
                    <div class="feature">
                        <div class="feature-icon">
                            <img src="http://1.bp.blogspot.com/-mSSuiaTIUb4/Ttb7tka8WYI/AAAAAAAAAIM/Vm1exUdjtbI/s1600/shutterstock_52491649.jpg" alt="WiFi">
                        </div>
                        <h3>Free WiFi</h3>
                        <p>High-speed internet throughout the property</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">
                            <img src="http://www.myaustinelite.com/wp-content/uploads/2015/01/terracotta-tiled-indoor-swimming-pool-designs.jpg" alt="Pool">
                        </div>
                        <h3>Swimming Pool</h3>
                        <p>Enjoy our infinity pool with stunning views</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">
                            <img src="https://tse1.mm.bing.net/th?id=OIP.YgvLm7f7-PSsptLwwGcAdwHaFS&pid=Api&P=0&h=180" alt="Restaurant">
                        </div>
                        <h3>Fine Dining</h3>
                        <p>Gourmet cuisine prepared by world-class chefs</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">
                            <img src="https://newsharper.com/wp-content/uploads/2020/01/the-grand-wailea-1200x800.jpg" alt="Spa">
                        </div>
                        <h3>Luxury Spa</h3>
                        <p>Rejuvenate with our premium spa treatments</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rooms-preview">
            <div class="container">
                <h2 class="section-title">Our Rooms</h2>
                <div class="rooms-grid">
                    <?php
                    // Display featured rooms (limit to 3)
                    $featuredRooms = getFeaturedRooms($conn, 3);
                    foreach ($featuredRooms as $room) {
                        echo '<div class="room-card">';
                        echo '<div class="room-image">';
                        echo '<img src="' . $room['image_url'] . '" alt="' . $room['name'] . '">';
                        echo '</div>';
                        echo '<div class="room-details">';
                        echo '<h3>' . $room['name'] . '</h3>';
                        echo '<p class="room-price">$' . $room['price_per_night'] . ' <span>per night</span></p>';
                        echo '<p class="room-description">' . substr($room['description'], 0, 100) . '...</p>';
                        echo '<a href="room-details.php?id=' . $room['id'] . '" class="btn btn-secondary">View Details</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="text-center">
                    <a href="rooms.php" class="btn btn-outline">View All Rooms</a>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <div class="container">
                <h2 class="section-title">Guest Experiences</h2>
                <div class="testimonials-slider">
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p>"The service was impeccable and the room exceeded all expectations. Will definitely return!"</p>
                        </div>
                        <div class="testimonial-author">
                            <p>- Sarah Johnson</p>
                        </div>
                    </div>
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p>"From check-in to check-out, everything was perfect. The food was absolutely delicious."</p>
                        </div>
                        <div class="testimonial-author">
                            <p>- Michael Chen</p>
                        </div>
                    </div>
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p>"A truly luxurious experience. The staff made us feel like royalty throughout our stay."</p>
                        </div>
                        <div class="testimonial-author">
                            <p>- Emma Richards</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>

</html>