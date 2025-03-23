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
    <title>Edit Cars</title>
</head>
<body>
    <h1>Edit Cars</h1>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>
    <table>
        <tr>
            <th></th>
            <th>Brand</th>
            <th>Model</th>
            <th>Car Type</th>
            <th>Year</th>
            <th>License Plate</th>
            <th>Price Per Day</th>
            <th>Action</th>
        </tr>
        <?php foreach ($cars as $car): ?>
            <tr>
                <td><img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200"></td>
                <td><?= htmlspecialchars($car['brand']) ?></td>
                <td><?= htmlspecialchars($car['model']) ?></td>
                <td><?= htmlspecialchars($car['car_type']) ?></td>
                <td><?= htmlspecialchars($car['year']) ?></td>
                <td><?= htmlspecialchars($car['license_plate']) ?></td>
                <td><?= htmlspecialchars($car['price_per_day']) ?></td>
                <td>
                    <a target="_blank" href="actual_edit_cars.php?id=<?= $car['id'] ?>">Edit Car</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>