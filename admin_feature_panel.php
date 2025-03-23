<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "connection.php";

?>

<div class="admin-panel">
    <div class="inner-admin-panel">
        <a href="./admin_features/add_car.php">Add Car</a>
        <a href="./admin_features/remove_car.php">Remove Car</a>
        <a href="./admin_features/edit_car.php">Edit Cars</a>
        <a href="./admin_features/notifications_booking_req.php">Booking Requests</a>
        <a href="./admin_features/booked_cars.php">View Booked Cars</a>
    </div>
</div>
