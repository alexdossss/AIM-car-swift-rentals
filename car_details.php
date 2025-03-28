<?php
    include "connection.php";
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if session is active and if the role is set
    if (!isset($_SESSION['id'])) {
        die("You are not logged in.");
    }

    if (!isset($_GET['id'])) {
        die("Invalid car selection.");
    }

    $car_id = $_GET['id'];

    // Fetch car details from the database
    $query = $conn->prepare("SELECT * FROM cars WHERE id = :id");
    $query->bindParam(":id", $car_id, PDO::PARAM_INT);
    $query->execute();
    $car = $query->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Car not found.");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($car['year'] . ' ' . $car['brand'] . ' ' . $car['model']) ?> - Car Details</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/media-query.css">
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
</head>
<body>

    <!-- Desktop Header -->
    <div class="header-container">
        <?php 
        if (isset($_SESSION['id']) && $_SESSION['role'] === 'admin') {
            include 'includes/header-admin.php'; // Admin header
        } else {
            include 'includes/header.php'; // Regular user header
        }
        ?>
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <?php 
        if (isset($_SESSION['id']) && $_SESSION['role'] === 'admin') {
            include 'includes/hamburger-admin.php'; // Admin mobile hamburger menu
        } else {
            include 'includes/hamburger-user.php'; // Regular user mobile hamburger menu
        }
        ?>
    </div>

    <!-- Car Details Section -->
    <div class="car-details-container">
        <div class="car-details-wrapper">
        
            <!-- Car Image Section -->
            <div class="car-details-image">
                <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image">
            </div>

            <!-- Car Information Section -->
            <div class="car-details-info">
                <h1 class="car-title"><?= htmlspecialchars($car['year'] . ' ' . $car['brand'] . ' ' . $car['model']) ?></h1>
                
                <!-- Rating Image -->
                <div class="car-rating">
                    <img src="uploads/five-star.png" alt="Rating">
                </div>

                <!-- Price -->
                <p class="car-price"><strong>₱<?= htmlspecialchars($car['price_per_day']) ?>/day</strong></p>

                <!-- Car Features & Description -->
                <ul class="car-features">
                    <li><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></li>
                    <?php
                    // Convert description into bullet points
                    $description_items = explode("\n", $car['description']);
                    foreach ($description_items as $item) {
                        echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                    }
                    ?>
                </ul>

                <!-- Book Now Button -->
                <a class="book-btn" href="booking_fill_up.php?id=<?= htmlspecialchars($car['id']) ?>">BOOK NOW</a>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

</body>
</html>
