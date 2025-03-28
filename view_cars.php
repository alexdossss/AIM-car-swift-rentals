<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "connection.php";

$query = $conn->prepare("SELECT * FROM cars");
$query->execute();
$cars = $query->fetchAll(PDO::FETCH_ASSOC);

function filterCarsByType($cars, $type)
{
    return array_filter($cars, function ($car) use ($type) {
        return strtoupper($car['car_type']) === strtoupper($type) && $car['status'] === "available";
    });
}

$categories = ["SEDAN", "SUV", "PICK-UP", "VAN", "COMMERCIAL"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Cars | AIM Swift Car Rentals</title>
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h1 class="admin-dashboard-title">Available Cars</h1>

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
                                <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" style="text-decoration: none; color: inherit;">
                                    <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image">
                                    <div class="car-info">
                                        <h3><?= htmlspecialchars($car['year']) . " " . htmlspecialchars($car['brand']) . " " . htmlspecialchars($car['model']) ?></h3>
                                    </div>
                                    <p><?= nl2br(htmlspecialchars($car['description'])) ?></p>
                                    <hr>
                                    <div class="car-footer">
                                        <span class="price">$<?= htmlspecialchars($car['price_per_day']) ?>/day</span>
                                        <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" class="rent-now">View Car</a>
                                    </div>
                                </a>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="category-section">
                    <h3 class="category-title" style="color: white;"><?= htmlspecialchars($category) ?></h3>
                    <p style="color: red;">No cars available.</p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>

</html>