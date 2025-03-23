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
    <title>Add Car</title>
</head>
<body>
    <h1>Add Car</h1>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>
    <form action="add_car.php" method="post" enctype="multipart/form-data">
        <label>Select Car Type:</label><br>
        <input type="radio" id="sedan" name="car_type" value="SEDAN" required> <label for="sedan">Sedan</label><br>
        <input type="radio" id="suv" name="car_type" value="SUV"> <label for="suv">SUV</label><br>
        <input type="radio" id="pickup" name="car_type" value="PICK-UP"> <label for="pickup">Pick-Up</label><br>
        <input type="radio" id="van" name="car_type" value="VAN"> <label for="van">Van</label><br>
        <input type="radio" id="commercial" name="car_type" value="COMMERCIAL"> <label for="commercial">Commercial</label><br>

        <label for="car_brand">Car Brand</label>
        <input type="text" id="car_brand" name="car_brand" required><br>

        <label for="car_model">Car Model</label>
        <input type="text" id="car_model" name="car_model" required><br>

        <label for="car_year">Car Year</label>
        <input type="number" id="car_year" name="car_year" required><br>

        <label for="car_license_plate">Car License Plate</label>
        <input type="text" id="car_license_plate" name="car_license_plate" required><br>

        <label for="price_per_day">Price per Day</label>
        <input type="number" id="price_per_day" name="price_per_day" required><br>

        <label for="car_description">Car Description</label>
        <textarea id="car_description" name="car_description" required></textarea><br>

        <label>Upload Image 1:</label>
        <input type="file" name="image1" accept="image/*"><br>

        <button type="submit">Add Car</button>
    </form>
</body>
</html>
