<?php
    include "../connection.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST['booking_id']) || empty($_POST['booking_id']) || !isset($_POST['car_id']) || empty($_POST['car_id'])) {
            echo "<script>alert('Error: Missing booking information.'); window.history.back();</script>";
            exit();
        }

        if (!isset($_SESSION['id'])) {
            echo "<script>alert('Error: You must be logged in.'); window.history.back();</script>";
            exit();
        }

        $user_id = $_SESSION['id'];
        $booking_id = intval($_POST['booking_id']);
        $car_id = intval($_POST['car_id']);

        $deleteQuery = $conn->prepare("DELETE FROM bookings WHERE id = :booking_id AND user_id = :user_id AND car_id = :car_id");
        $deleteQuery->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $deleteQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $deleteQuery->bindParam(':car_id', $car_id, PDO::PARAM_INT);

        if ($deleteQuery->execute()) {
            echo "<script>alert('Booking successfully canceled.'); window.location.href = 'cancel_pending_rent.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to cancel booking.'); window.history.back();</script>";
        }
    }
?>