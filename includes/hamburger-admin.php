<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sidebar Menu</title>
    <link rel="stylesheet" href="../css/media-query.css">
</head>

<body>

    <header class="hamburger-header-container">
        <div class="hamburger-menu-toggle-main" id="hamburger-icon-clickable">
            ☰
        </div>
        <div class="hamburger-logo">
            <a href="dashboard_admin.php"><img src="uploads/logo-transparent.svg" alt="AIM SWIFT Car Rentals"></a>
        </div>
    </header>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-navigation-drawer-main" id="sidebar-panel-drawer">
        <button class="sidebar-close-button-main" id="sidebar-close-button-clickable">&times;</button>
        <ul class="sidebar-navigation-links-listing">
            <li><a href="dashboard_admin.php">Dashboard</a></li>
            <li><a href="admin_features/add_car.php">Add Car</a></li>
            <li><a href="admin_features/remove_car.php">Remove Car</a></li>
            <li><a href="admin_features/edit_car.php">Edit Car</a></li>
            <li><a href="admin_features/notifications_booking_req.php">Booking Requests</a></li>
            <li><a href="admin_features/booked_cars.php">Booked Cars</a></li>
            <li><a href="/aim_swift_car_rentals/logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a></li>
        </ul>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.getElementById("hamburger-icon-clickable");
            const sidebar = document.getElementById("sidebar-panel-drawer");
            const closeBtn = document.getElementById("sidebar-close-button-clickable");

            hamburger.addEventListener("click", function() {
                sidebar.classList.add("active");
            });

            closeBtn.addEventListener("click", function() {
                sidebar.classList.remove("active");
            });

            document.addEventListener("click", function(event) {
                if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                    sidebar.classList.remove("active");
                }
            });
        });
    </script>

</body>

</html>