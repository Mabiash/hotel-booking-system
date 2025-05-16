<?php
include_once 'includes/config.php';
include_once 'includes/functions.php';
// Function to get a single room by ID
// Validate and get room ID from query param
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid room ID.");
}

$room_id = (int)$_GET['id'];
$room = getRoomById($conn, $room_id);

if (!$room) {
    die("Room not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo htmlspecialchars($room['name']); ?> - Room Details</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9fafc;
    color: #2c3e50;
    margin: 0;
    padding: 0;
  }
  .container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    overflow: hidden;
  }
  .room-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    display: block;
  }
  .room-content {
    padding: 30px 40px;
  }
  h1 {
    margin-top: 0;
    font-size: 2.5rem;
    font-weight: 700;
    color: #34495e;
  }
  .room-type {
    color: #7f8c8d;
    font-size: 1.1rem;
    margin-bottom: 20px;
  }
  .description {
    line-height: 1.6;
    font-size: 1rem;
    color: #555;
    margin-bottom: 30px;
    white-space: pre-wrap;
  }
  .info-grid {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
  }
  .info-box {
    flex: 1 1 120px;
    background: #ecf0f1;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: inset 0 0 8px rgba(0,0,0,0.05);
  }
  .info-box h3 {
    margin: 0 0 10px 0;
    font-size: 1.2rem;
    color: #2980b9;
  }
  .info-box p {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    color: #2c3e50;
  }
  .status {
    margin-top: 20px;
    font-weight: 600;
    color: white;
    padding: 8px 16px;
    border-radius: 25px;
    display: inline-block;
    font-size: 1rem;
  }
  .status.Available {
    background-color: #27ae60;
  }
  .status.Unavailable {
    background-color: #c0392b;
  }
  @media (max-width: 600px) {
    .room-content {
      padding: 20px;
    }
    .info-grid {
      flex-direction: column;
    }
  }
</style>
</head>
<body>
  
  <div class="container" role="main">
    <?php if (!empty($room['image_url'])): ?>
      <img
        src="<?php echo htmlspecialchars($room['image_url']); ?>"
        alt="<?php echo htmlspecialchars($room['name']); ?>"
        class="room-image"
        loading="lazy"
        decoding="async"
      />
    <?php endif; ?>
    <div class="room-content">
      <h1><?php echo htmlspecialchars($room['name']); ?></h1>
      <div class="room-type"><?php echo htmlspecialchars($room['type']); ?></div>
      <div class="description"><?php echo nl2br(htmlspecialchars($room['description'])); ?></div>
      <div class="info-grid" role="list">
        <div class="info-box" role="listitem" aria-label="Maximum Occupancy">
          <h3>Max Occupancy</h3>
          <p><?php echo (int)$room['max_occupancy']; ?> Guests</p>
        </div>
        <div class="info-box" role="listitem" aria-label="Price Per Night">
          <h3>Price Per Night</h3>
          <p>$<?php echo number_format($room['price_per_night'], 2); ?></p>
        </div>
        <div class="info-box" role="listitem" aria-label="Room Size">
          <h3>Size</h3>
          <p><?php echo (int)$room['size']; ?> m²</p>
        </div>
        <div class="info-box" role="listitem" aria-label="Room View">
          <h3>View</h3>
          <p><?php echo htmlspecialchars($room['view']); ?></p>
        </div>
      </div>
      <div class="status <?php echo htmlspecialchars($room['status']); ?>" aria-label="Availability Status">
        <?php echo htmlspecialchars($room['status']); ?>
      </div>
    </div>
  </div>
</body>
</html>
