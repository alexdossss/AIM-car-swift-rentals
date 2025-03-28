<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$noHeaderPages = ['login.php', 'signup.php'];
$bodyClass = in_array($currentPage, $noHeaderPages) ? '' : 'admin-body-with-header';
?>

<head>
    <link rel="stylesheet" href="../css/media-query.css">
</head>

<body class="<?php echo $bodyClass; ?>">
    <header class="admin-header">
        <div class="admin-header-container">
            <div class="admin-logo">
                <a href="../dashboard_admin.php"><img src="../uploads/logo-transparent.svg" alt="AIM SWIFT Car Rentals" width="130"></a>
            </div>

            <nav class="admin-nav">
                <a href="../dashboard_admin.php">Dashboard</a>
                <a href="add_car.php">Add Car</a>
                <a href="remove_car.php">Remove Car</a>
                <a href="edit_car.php">Edit Car</a>
                <a href="notifications_booking_req.php">Booking Requests</a>
                <a href="booked_cars.php">Booked Cars</a>
            </nav>

            <div class="admin-auth">
                <a href="/aim_swift_car_rentals/logout.php" onclick="return confirm('Are you sure you want to log out?')">LOGOUT</a>
            </div>
        </div>
    </header>