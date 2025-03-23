<?php
    include "../connection.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['booking_id'], $_POST['car_id'])) {
        $booking_id = $_POST['booking_id'];
        $car_id = $_POST['car_id'];

        try {
            $stmt = $conn->prepare("UPDATE booking_history SET status = 'returned' WHERE id = ?");
            $stmt->execute([$booking_id]);

            $stmt = $conn->prepare("UPDATE cars SET status = 'available' WHERE id = ?");
            $stmt->execute([$car_id]);

            header("Location: booked_cars.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid request.";
    }
?>
