<?php
    include "connection.php";
    include "header.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_GET['id'])) {
        die("Invalid car selection.");
    }

    $car_id = $_GET['id'];

    $query = $conn->prepare("SELECT * FROM cars WHERE id = :id");
    $query->bindParam(":id", $car_id, PDO::PARAM_INT);
    $query->execute();
    $car = $query->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Car not found.");
    }
    $dashboard_url = "view_cars.php"; 
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'admin') {
            $dashboard_url = "dashboard_admin.php"; 
        } else {
            $dashboard_url = "dashboard_user.php"; 
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($car['model']) ?> - Car Details</title>
</head>
<body>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>
    <h1><?= htmlspecialchars($car['model']) ?></h1>
    <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
    <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
    <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
    <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>
    <p><strong>Description</strong><?= htmlspecialchars($car['description']) ?></p>

    <a target="_blank" href="booking_fill_up.php?id=<?= htmlspecialchars($car['id']) ?>" style="text-decoration: none; color: inherit;">
        Book Now
    </a>
    
</body>
</html>
