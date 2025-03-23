<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "connection.php";

    $query = $conn->prepare("SELECT * FROM cars");
    $query->execute();
    $cars = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <h1>Available Cars</h1>

        <div id="main-container">

            <!-- SEDAN -->
            <div id="sedan">
                <h3>SEDAN</h3>

                <?php 
                $Sedan_cars = array_filter($cars, function($car) {
                    return $car['car_type'] == "SEDAN" && $car['status'] == "available"; 
                });

                if (!empty($Sedan_cars)): ?>
                    <?php foreach ($Sedan_cars as $car): ?>
                        <div class="car-card">
                            <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" style="text-decoration: none; color: inherit;">
                                <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="300">
                                <h3><?= htmlspecialchars($car['model']) ?></h3>
                                <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                                <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: red;">No cars available.</p>
                <?php endif; ?>
            </div>

            <!-- SUV -->
            <div id="suv">
                <h3>SUV</h3>

                <?php 
                $Suv_cars = array_filter($cars, function($car) {
                    return $car['car_type'] == "SUV" && $car['status'] == "available";
                });

                if (!empty($Suv_cars)): ?>
                    <?php foreach ($Suv_cars as $car): ?>
                        <div class="car-card">
                            <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" style="text-decoration: none; color: inherit;">
                                <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="300">
                                <h3><?= htmlspecialchars($car['model']) ?></h3>
                                <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                                <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: red;">No cars available.</p>
                <?php endif; ?>
            </div>

            <!-- PICKUP -->
            <div id="pickup">
                <h3>Pickup</h3>

                <?php 
                $pickup_cars = array_filter($cars, function($car) {
                    return $car['car_type'] == "PICK-UP" && $car['status'] == "available";
                });

                if (!empty($pickup_cars)): ?>
                    <?php foreach ($pickup_cars as $car): ?>
                        <div class="car-card">
                            <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" style="text-decoration: none; color: inherit;">
                                <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="300">
                                <h3><?= htmlspecialchars($car['model']) ?></h3>
                                <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                                <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: red;">No cars available.</p>
                <?php endif; ?>
            </div>

            <!-- VAN -->
            <div id="Van">
                <h3>Van</h3>

                <?php 
                $van_cars = array_filter($cars, function($car) {
                    return $car['car_type'] == "VAN" && $car['status'] == "available";
                });

                if (!empty($van_cars)): ?>
                    <?php foreach ($van_cars as $car): ?>
                        <div class="car-card">
                            <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" style="text-decoration: none; color: inherit;">
                                <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="300">
                                <h3><?= htmlspecialchars($car['model']) ?></h3>
                                <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                                <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: red;">No cars available.</p>
                <?php endif; ?>
            </div>
            
            <!-- COMMERCIAL -->
            <div id="commercial">
                <h3>Commercial</h3>

                <?php 
                $commercial_cars = array_filter($cars, function($car) {
                    return $car['car_type'] == "COMMERCIAL" && $car['status'] == "available";
                });

                if (!empty($commercial_cars)): ?>
                    <?php foreach ($commercial_cars as $car): ?>
                        <div class="car-card">
                            <a href="car_details.php?id=<?= htmlspecialchars($car['id']) ?>" style="text-decoration: none; color: inherit;">
                                <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="300">
                                <h3><?= htmlspecialchars($car['model']) ?></h3>
                                <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                                <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: red;">No cars available.</p>
                <?php endif; ?>
            </div>


        </div>


</body>
</html>