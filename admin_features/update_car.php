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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo "<script>alert('Access denied.'); window.location.href = '../index.php';</script>";
            exit();
        }

        $id = intval($_POST['id']);
        $brand = $_POST['brand'];
        $model = $_POST['model'];
        $car_type = $_POST['car_type'];
        $year = $_POST['year'];
        $license_plate = $_POST['license_plate'];
        $price_per_day = $_POST['price_per_day'];
        $description = $_POST['description'];

        try {
            $query = $conn->prepare("UPDATE cars SET brand=?, model=?, car_type=?, year=?, license_plate=?, price_per_day=?, description=? WHERE id=?");
            $query->execute([$brand, $model, $car_type, $year, $license_plate, $price_per_day, $description, $id]);

            foreach ($_FILES as $key => $file) {
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $fileData = file_get_contents($file["tmp_name"]);
                    $column = htmlspecialchars($key); 

                    $imgQuery = $conn->prepare("UPDATE cars SET $column = :image WHERE id = :car_id");
                    $imgQuery->bindParam(":image", $fileData, PDO::PARAM_LOB);
                    $imgQuery->bindParam(":car_id", $id, PDO::PARAM_INT);
                    $imgQuery->execute();
                }
            }

            echo "<script>alert('Car updated successfully!'); window.location.href = '$dashboard_url';</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>
