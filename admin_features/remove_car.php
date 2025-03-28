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
            header("Location: $dashboard_url");
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
    <title>Remove Car | AIM Swift Car Rentals</title>
    <link rel="icon" href="../uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/media-query.css">
</head>

<body>
    <div class="remove-car-container">

        <!-- Desktop Header -->
        <div class="header-container">
            <?php include "../includes/header-admin2.php"; ?>
        </div>

        <!-- Mobile Header -->
        <div class="mobile-header">
            <?php include "../includes/hamburger-admin2.php"; ?>
        </div>

        <h1 class="remove-car-title">REMOVE CAR</h1>

        <?php
        $categories = ["SEDAN", "SUV", "PICK-UP", "VAN", "COMMERCIAL"];
        foreach ($categories as $category):
            $filtered_cars = array_filter($cars, fn($car) => $car['car_type'] == $category);
        ?>
            <div class="car-category">
                <h3><?= $category ?></h3>
                <div class="car-grid">
                    <?php if (!empty($filtered_cars)): ?>
                        <?php foreach ($filtered_cars as $car): ?>
                            <div class="remove-car-card">
                                <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image">
                                <div class="remove-car-info">
                                    <h3><?= htmlspecialchars($car['year']) ?> <?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?></h3>
                                    <p class="price"><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
                                </div>
                                <div class="car-footer">
                                    <form action="remove_car.php" method="post" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                        <button type="submit" class="remove-car-btn">REMOVE</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-cars">No cars available.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Do you really want to delete this car?");
        }
    </script>

    <?php include "../includes/footer-2.php"; ?>
    
</body>

</html>