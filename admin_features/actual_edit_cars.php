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
    <title>Edit Car | AIM Swift Car Rentals</title>
    <link rel="icon" href="../uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
</head>



<body class="edit-car-page">

    <!-- Desktop Header -->
    <div class="header-container">
        <?php include "../includes/header-admin2.php"; ?>
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <?php include "../includes/hamburger-admin2.php"; ?>
    </div>


    <div class="edit-car-container">
        <h1 class="edit-car-title">EDIT CAR</h1>


        <form action="update_car.php" method="POST" enctype="multipart/form-data" class="edit-car-form">
            <input type="hidden" name="id" value="<?= $car['id'] ?>">

            <div class="edit-car-form-group">
                <div class="edit-car-image-container">
                    <img src="../display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" class="edit-car-current-image">
                </div>
            </div>


            <div class="edit-car-form-group edit-car-file-upload">
                <label for="image1">Upload New Image:</label>
                <input type="file" id="image1" name="image1" accept="image/*" hidden>
                <button type="button" class="edit-car-upload-btn" onclick="document.getElementById('image1').click()">Choose File</button>
                <span id="file-name" style="color: white; margin-left: 10px;"></span>
            </div>

            <script>
                document.getElementById("image1").addEventListener("change", function() {
                    let fileName = this.files.length > 0 ? this.files[0].name : "No file chosen";
                    document.getElementById("file-name").textContent = fileName;
                });
            </script>

            <div class="edit-car-form-group">
                <label>Brand:</label>
                <input type="text" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required class="edit-car-input">
            </div>

            <div class="edit-car-form-group">
                <label>Model:</label>
                <input type="text" name="model" value="<?= htmlspecialchars($car['model']) ?>" required class="edit-car-input">
            </div>

            <div class="edit-car-form-group">
                <label>Car Type:</label>
                <select name="car_type" class="edit-car-select">
                    <option <?= $car['car_type'] == 'SUV' ? 'selected' : '' ?>>SUV</option>
                    <option <?= $car['car_type'] == 'SEDAN' ? 'selected' : '' ?>>SEDAN</option>
                    <option <?= $car['car_type'] == 'PICK-UP' ? 'selected' : '' ?>>PICK-UP</option>
                    <option <?= $car['car_type'] == 'VAN' ? 'selected' : '' ?>>VAN</option>
                    <option <?= $car['car_type'] == 'COMMERCIAL' ? 'selected' : '' ?>>COMMERCIAL</option>
                </select>
            </div>

            <div class="edit-car-form-group">
                <label>Year:</label>
                <input type="number" name="year" value="<?= htmlspecialchars($car['year']) ?>" required class="edit-car-input">
            </div>

            <div class="edit-car-form-group">
                <label>License Plate:</label>
                <input type="text" name="license_plate" value="<?= htmlspecialchars($car['license_plate']) ?>" required class="edit-car-input">
            </div>

            <div class="edit-car-form-group">
                <label>Price Per Day:</label>
                <input type="number" name="price_per_day" value="<?= htmlspecialchars($car['price_per_day']) ?>" required class="edit-car-input">
            </div>

            <div class="edit-car-form-group">
                <label>Description:</label>
                <textarea name="description" required class="edit-car-textarea"><?= htmlspecialchars($car['description']) ?></textarea>
            </div>

            <button type="submit" class="edit-car-submit-btn">Update Car</button>
        </form>
    </div>

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

    <?php include "../includes/footer-2.php"; ?>
</body>

</html>