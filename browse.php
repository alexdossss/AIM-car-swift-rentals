<?php
session_start();
include "connection.php";

$query = $conn->prepare("SELECT * FROM cars WHERE status = 'available'");
$query->execute();
$cars = $query->fetchAll(PDO::FETCH_ASSOC);

function filterCarsByType($cars, $type)
{
    return array_filter($cars, function ($car) use ($type) {
        return strtoupper($car['car_type']) === strtoupper($type);
    });
}

$categories = ["SEDAN", "SUV", "PICK-UP", "VAN", "COMMERCIAL"];
$isLoggedIn = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Cars | AIM Swift Car Rentals</title>
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/media-query.css">
</head>

<body>
    <!-- Desktop Header -->
    <div class="header-container">
        <?php include "includes/header.php"; ?>
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <?php include "includes/hamburger-user.php"; ?>
    </div>

    <h1 class="admin-dashboard-title">Available Cars</h1>
    <h2 class="subheader-browse">Check out our group categories</h2>

    <div class="admin-dashboard">
        <div id="main-container">
            <?php foreach ($categories as $category): ?>
                <?php
                $filteredCars = filterCarsByType($cars, $category);
                if (!empty($filteredCars)):
                ?>
                    <div class="category-section">
                        <h3 class="category-title"><?= htmlspecialchars($category) ?></h3>
                        <div class="car-grid">
                            <?php foreach ($filteredCars as $car): ?>
                                <div class="user-car-card">
                                    <a href="<?= $isLoggedIn ? "car_details.php?id=" . htmlspecialchars($car['id']) : "#" ?>"
                                        style="text-decoration: none; color: inherit;"
                                        <?= !$isLoggedIn ? 'onclick="return showLoginAlert(event);"' : '' ?>>

                                        <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" class="car-image">

                                        <div class="car-info">
                                            <h3><?= htmlspecialchars($car['year']) . " " . htmlspecialchars($car['brand']) . " " . htmlspecialchars($car['model']) ?></h3>
                                        </div>

                                        <p class="car-description"><?= nl2br(htmlspecialchars($car['description'])) ?></p>
                                        <hr>

                                        <div class="car-footer">
                                            <span class="price">$<?= htmlspecialchars($car['price_per_day']) ?>/day</span>
                                            <a class="rent-now" href="<?= $isLoggedIn ? "car_details.php?id=" . htmlspecialchars($car['id']) : "#" ?>"
                                                <?= !$isLoggedIn ? 'onclick="return showLoginAlert(event);"' : '' ?>>View Car</a>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="category-section">
                        <h3 class="category-title"><?= htmlspecialchars($category) ?></h3>
                        <p class="no-cars-message">No cars available.</p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function showLoginAlert(event) {
            event.preventDefault();
            alert("You must log in or register to view/book this car.");
            window.location.href = "login.php";
            return false;
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>

</html>