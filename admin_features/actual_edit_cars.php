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

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo "<script>alert('Car ID is missing.'); window.location.href = '$dashboard_url';</script>";
        exit();
    }

    $car_id = intval($_GET['id']);
    $query = $conn->prepare("SELECT * FROM cars WHERE id = :id");
    $query->bindParam(':id', $car_id, PDO::PARAM_INT);
    $query->execute();
    $car = $query->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        echo "<script>alert('Car not found.'); window.location.href = '$dashboard_url';</script>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
</head>
<body>
    <h1>Edit Car</h1>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>
    <form action="update_car.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $car['id'] ?>">
        <p>Current Picture</p>
        <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
        <br>
        <label>Upload New Image:</label>
        <p>Image Preview</p>
        <img id="previewImage" src="#" alt="Image Preview" style="display: none; width: 200px;">
        <br>
        <input type="file" id="imageInput" name="image1" accept="image/*"><br>
        <br>
        <label>Brand:</label> <input type="text" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required><br>
        <label>Model:</label> <input type="text" name="model" value="<?= htmlspecialchars($car['model']) ?>" required><br>
        <label>Car Type:</label>
        <select name="car_type">
            <option <?= $car['car_type'] == 'SUV' ? 'selected' : '' ?>>SUV</option>
            <option <?= $car['car_type'] == 'SEDAN' ? 'selected' : '' ?>>SEDAN</option>
            <option <?= $car['car_type'] == 'PICK-UP' ? 'selected' : '' ?>>PICK-UP</option>
            <option <?= $car['car_type'] == 'VAN' ? 'selected' : '' ?>>VAN</option>
            <option <?= $car['car_type'] == 'COMMERCIAL' ? 'selected' : '' ?>>COMMERCIAL</option>
        </select><br>
        <label>Year:</label> <input type="number" name="year" value="<?= htmlspecialchars($car['year']) ?>" required><br>
        <label>License Plate:</label> <input type="text" name="license_plate" value="<?= htmlspecialchars($car['license_plate']) ?>" required><br>
        <label>Price Per Day:</label> <input type="number" name="price_per_day" value="<?= htmlspecialchars($car['price_per_day']) ?>" required><br>
        <label>Description:</label> <textarea name="description" required><?= htmlspecialchars($car['description']) ?></textarea><br>
        <button type="submit">Update Car</button>
    </form>


    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0]; 
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImage = document.getElementById('previewImage');
                    previewImage.src = e.target.result; 
                    previewImage.style.display = 'block'; 
                };
                reader.readAsDataURL(file); 
            }
        });
    </script>
</body>
</html>