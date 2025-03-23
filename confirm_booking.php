<?php
    include "connection.php";

    if (!isset($_GET['user_id']) || !isset($_GET['car_id'])) {
        die("Invalid confirmation link.");
    }

    $user_id = $_GET['user_id'];
    $car_id = $_GET['car_id'];

    $stmt = $conn->prepare("UPDATE bookings SET status = 'approved' WHERE user_id = :user_id AND car_id = :car_id AND status = 'pending'");
    $stmt->execute([":user_id" => $user_id, ":car_id" => $car_id]);

    echo "Booking confirmed! The admin will now review your request.";
?>
