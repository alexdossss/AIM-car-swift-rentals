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

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href = '../index.php';</script>";
    exit();
}

$query = $conn->prepare("SELECT * FROM cars ORDER BY id DESC");
$query->execute();
$cars = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car | AIM Swift Car Rentals</title>
    <link rel="icon" href="../uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/media-query.css">
</head>

<body>

    <div class="edit-cars-container">

        <!-- Desktop Header -->
        <div class="header-container">
            <?php include "../includes/header-admin2.php"; ?>
        </div>

        <!-- Mobile Header -->
        <div class="mobile-header">
            <?php include "../includes/hamburger-admin2.php"; ?>
        </div>

        <h1 class="edit-cars-title">EDIT CARS</h1>
        <table class="edit-cars-table">
            <thead>
                <tr>
                    <th class="edit-cars-header">Image</th>
                    <th class="edit-cars-header">Brand</th>
                    <th class="edit-cars-header">Model</th>
                    <th class="edit-cars-header">Car Type</th>
                    <th class="edit-cars-header">Year</th>
                    <th class="edit-cars-header">License Plate</th>
                    <th class="edit-cars-header">Price Per Day</th>
                    <th class="edit-cars-header">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr class="edit-cars-row">
                        <td class="edit-cars-image-cell">
                            <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>"
                                alt="Car Image" class="edit-cars-image">
                        </td>
                        <td class="edit-cars-data"><?= htmlspecialchars($car['brand']) ?></td>
                        <td class="edit-cars-data"><?= htmlspecialchars($car['model']) ?></td>
                        <td class="edit-cars-data"><?= htmlspecialchars($car['car_type']) ?></td>
                        <td class="edit-cars-data"><?= htmlspecialchars($car['year']) ?></td>
                        <td class="edit-cars-data"><?= htmlspecialchars($car['license_plate']) ?></td>
                        <td class="edit-cars-data">$<?= htmlspecialchars($car['price_per_day']) ?></td>
                        <td class="edit-cars-action">
                            <a target="_blank" href="actual_edit_cars.php?id=<?= $car['id'] ?>" class="edit-cars-btn">
                                EDIT CAR
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer-2.php'; ?>

</body>

</html>