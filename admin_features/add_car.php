<?php
include "../connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dashboard_url = "view_cars.php";
if (isset($_SESSION['role'])) {
    $dashboard_url = ($_SESSION['role'] === 'admin') ? "../dashboard_admin.php" : "../dashboard_user.php";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_type = $_POST['car_type'];
    $car_brand = $_POST['car_brand'];
    $car_model = $_POST['car_model'];
    $car_year = $_POST['car_year'];
    $car_license_plate = $_POST['car_license_plate'];
    $price_per_day = $_POST['price_per_day'];
    $car_description = $_POST['car_description'];

    try {
        $query = $conn->prepare("INSERT INTO cars (car_type, brand, model, year, license_plate, price_per_day, description, image1) 
                                VALUES (:car_type, :car_brand, :car_model, :car_year, :car_license_plate, :price_per_day, :car_description, :image1)");

        $query->bindParam(":car_type", $car_type, PDO::PARAM_STR);
        $query->bindParam(":car_brand", $car_brand, PDO::PARAM_STR);
        $query->bindParam(":car_model", $car_model, PDO::PARAM_STR);
        $query->bindParam(":car_year", $car_year, PDO::PARAM_INT);
        $query->bindParam(":car_license_plate", $car_license_plate, PDO::PARAM_STR);
        $query->bindParam(":price_per_day", $price_per_day, PDO::PARAM_INT);
        $query->bindParam(":car_description", $car_description, PDO::PARAM_STR);
        $query->bindParam(":image1", $imageData, PDO::PARAM_LOB);

        if ($query->execute()) {
            $car_id = $conn->lastInsertId();

            foreach ($_FILES as $key => $file) {
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $fileData = file_get_contents($file["tmp_name"]);
                    $column = htmlspecialchars($key);

                    $imgQuery = $conn->prepare("UPDATE cars SET $column = :image WHERE id = :car_id");
                    $imgQuery->bindParam(":image", $fileData, PDO::PARAM_LOB);
                    $imgQuery->bindParam(":car_id", $car_id, PDO::PARAM_INT);
                    $imgQuery->execute();
                }
            }

            echo "<script>alert('Car added successfully!'); window.location.href = '$dashboard_url';</script>";
        } else {
            echo "<script>alert('Error adding car.'); window.history.back();</script>";
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
    <link rel="icon" href="../uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Add Car | AIM Swift Car Rentals</title>
</head>

<body>
    <div class="add-car-container">

        <!-- Desktop Header -->
        <div class="header-container">
            <?php include "../includes/header-admin2.php"; ?>
        </div>

        <!-- Mobile Header -->
        <div class="mobile-header">
            <?php include "../includes/hamburger-admin2.php"; ?>
        </div>

        <h1 class="add-car-title">ADD NEW CAR</h1>

        <form action="add_car.php" method="post" enctype="multipart/form-data" class="add-car-form">
            <div class="add-car-form-group">
                <label>Select Car Type:</label>
                <div class="add-car-radio-group">
                    <input type="radio" id="sedan" name="car_type" value="SEDAN" required> <label for="sedan">Sedan</label>
                    <input type="radio" id="suv" name="car_type" value="SUV"> <label for="suv">SUV</label>
                    <input type="radio" id="pickup" name="car_type" value="PICK-UP"> <label for="pickup">Pick-Up</label>
                    <input type="radio" id="van" name="car_type" value="VAN"> <label for="van">Van</label>
                    <input type="radio" id="commercial" name="car_type" value="COMMERCIAL"> <label for="commercial">Commercial</label>
                </div>
            </div>

            <div class="add-car-form-group">
                <label for="car_brand">Car Brand</label>
                <input type="text" id="car_brand" name="car_brand" required>
            </div>

            <div class="add-car-form-group">
                <label for="car_model">Car Model</label>
                <input type="text" id="car_model" name="car_model" required>
            </div>

            <div class="add-car-form-group">
                <label for="car_year">Car Year</label>
                <input type="number" id="car_year" name="car_year" required>
            </div>

            <div class="add-car-form-group">
                <label for="car_license_plate">Car License Plate</label>
                <input type="text" id="car_license_plate" name="car_license_plate" required>
            </div>

            <div class="add-car-form-group">
                <label for="price_per_day">Price per Day</label>
                <input type="number" id="price_per_day" name="price_per_day" required>
            </div>

            <div class="add-car-form-group">
                <label for="car_description">Car Description</label>
                <textarea id="car_description" name="car_description" required></textarea>
            </div>

            <div class="add-car-form-group add-car-file-upload">
                <label for="image1">Upload Image:</label>
                <input type="file" id="image1" name="image1" accept="image/*" hidden>
                <button type="button" class="add-car-upload-btn" onclick="document.getElementById('image1').click()">Choose File</button>
                <span id="file-name" style="color: white; margin-left: 10px;"></span>
            </div>

            <script>
                document.getElementById("image1").addEventListener("change", function() {
                    let fileName = this.files.length > 0 ? this.files[0].name : "No file chosen";
                    document.getElementById("file-name").textContent = fileName;
                });
            </script>


            <button type="submit" class="add-car-submit-btn">ADD CAR</button>
        </form>
    </div>

    <?php include '../includes/footer-2.php'; ?>
</body>

</html>