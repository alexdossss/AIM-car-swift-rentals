<?php
    include "connection.php";

    if (isset($_GET['id'])) {
        $car_id = $_GET['id'];

        $query = $conn->prepare("SELECT image1 FROM cars WHERE id = :id");
        $query->bindParam(":id", $car_id, PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if ($data && !empty($data['image1'])) {
            $image = $data['image1'];

            if (strpos($image, '<svg') !== false) {
                header("Content-Type: image/svg+xml"); 
                echo $image; 
            } else {
                header("Content-Type: image/jpeg");
                echo $image;
            }
            exit;
        }
    }
    echo "Image not found!";
?>
