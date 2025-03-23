<?php
    include "../connection.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $dashboard_url = "view_cars.php"; 
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'admin') {
            $dashboard_url = "../dashboard_admin.php"; 
        } else {
            $dashboard_url = "../dashboard_user.php"; 
        }
    }

    $query = $conn->prepare("SELECT * FROM cars");
    $query->execute();
    $cars = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["car_id"])) {
        $car_id = $_POST["car_id"];

        try {
            $deleteQuery = $conn->prepare("DELETE FROM cars WHERE id = :car_id");
            $deleteQuery->bindParam(":car_id", $car_id, PDO::PARAM_INT);
            
            if ($deleteQuery->execute()) {
                header("Location:  $dashboard_url");
                exit();
            } else {
                header("Location:  $dashboard_url");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Remove Car</h1>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>

    <div>
        <!-- Sedans -->
        <div id="sedan">
            <h3>Sedans</h3>
            <?php 
            $sedan_cars = array_filter($cars, fn($car) => $car['car_type'] == "SEDAN");

            if (!empty($sedan_cars)): ?>
                <?php foreach ($sedan_cars as $car): ?>
                    <div class="car-card">
                        <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
                        <h3><?= htmlspecialchars($car['model']) ?></h3>
                        <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                        <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                        <form action="remove_car.php" method="post" onsubmit="return confirmDelete()">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: red;">No cars available.</p>
            <?php endif; ?>
        </div>

        <!-- SUVs -->
        <div id="suv">
            <h3>SUVs</h3>
            <?php 
            $suv_cars = array_filter($cars, fn($car) => $car['car_type'] == "SUV");

            if (!empty($suv_cars)): ?>
                <?php foreach ($suv_cars as $car): ?>
                    <div class="car-card">
                        <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
                        <h3><?= htmlspecialchars($car['model']) ?></h3>
                        <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                        <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                        <form action="remove_car.php" method="post" onsubmit="return confirmDelete()">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: red;">No cars available.</p>
            <?php endif; ?>
        </div>

        <!-- Pick-Up Trucks -->
        <div id="pickup">
            <h3>Pick-Up Trucks</h3>
            <?php 
            $pickup_cars = array_filter($cars, fn($car) => $car['car_type'] == "PICK-UP");

            if (!empty($pickup_cars)): ?>
                <?php foreach ($pickup_cars as $car): ?>
                    <div class="car-card">
                        <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
                        <h3><?= htmlspecialchars($car['model']) ?></h3>
                        <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                        <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                        <form action="remove_car.php" method="post" onsubmit="return confirmDelete()">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: red;">No cars available.</p>
            <?php endif; ?>
        </div>

        <!-- Vans -->
        <div id="van">
            <h3>Vans</h3>
            <?php 
            $van_cars = array_filter($cars, fn($car) => $car['car_type'] == "VAN");

            if (!empty($van_cars)): ?>
                <?php foreach ($van_cars as $car): ?>
                    <div class="car-card">
                        <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
                        <h3><?= htmlspecialchars($car['model']) ?></h3>
                        <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                        <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                        <form action="remove_car.php" method="post" onsubmit="return confirmDelete()">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: red;">No cars available.</p>
            <?php endif; ?>
        </div>

        <!-- Commercial Vehicles -->
        <div id="commercial">
            <h3>Commercial Vehicles</h3>
            <?php 
            $commercial_cars = array_filter($cars, fn($car) => $car['car_type'] == "COMMERCIAL");

            if (!empty($commercial_cars)): ?>
                <?php foreach ($commercial_cars as $car): ?>
                    <div class="car-card">
                        <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
                        <h3><?= htmlspecialchars($car['model']) ?></h3>
                        <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
                        <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                        <form action="remove_car.php" method="post"  onsubmit="return confirmDelete()">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: red;">No cars available.</p>
            <?php endif; ?>
        </div>

    </div>
    <script>
        function confirmDelete() {
            if (confirm("Do you really want to delete this car?")) {
                alert("Car deleted successfully!");
                return true;
            }
            return false;
        }
    </script>
</body>
</html>