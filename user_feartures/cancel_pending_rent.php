<?php
include "../connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    echo "<p style='color: red; text-align: center;'>You must be logged in to view this page.</p>";
    exit();
}

$user_id = $_SESSION['id'];

$query = $conn->prepare("SELECT bookings.id AS booking_id, bookings.car_id, bookings.start_date, bookings.end_date, 
        bookings.total_price, bookings.status, cars.brand, cars.model
        FROM bookings 
        INNER JOIN cars ON bookings.car_id = cars.id  
        WHERE bookings.user_id = :user_id AND bookings.status = 'approved'");
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$approvedBookings = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Bookings | AIM Swift Car Rentals</title>
    <link rel="icon" href="../uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/media-query.css">
</head>

<body>

    <!-- Desktop Header -->
    <div class="header-container">
        <?php include "../includes/header-2.php"; ?>
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <?php include "../includes/hamburger-user2.php"; ?>
    </div>

    <div class="pending-rent-container">
        <h1><?= htmlspecialchars($_SESSION['full_name']) ?>'s Approved Car Rentals</h1>

        <?php if (!empty($approvedBookings)): ?>
            <div class="pending-rent-table-wrapper">
                <table class="pending-rent-table">
                    <tr>
                        <th>Car Brand</th>
                        <th>Model</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($approvedBookings as $rent): ?>
                        <tr>
                            <td><?= htmlspecialchars($rent['brand']) ?></td>
                            <td><?= htmlspecialchars($rent['model']) ?></td>
                            <td><?= htmlspecialchars($rent['start_date']) ?></td>
                            <td><?= htmlspecialchars($rent['end_date']) ?></td>
                            <td><?= number_format($rent['total_price'], 2) ?></td>
                            <td>
                                <form method="POST" action="cancel_booking.php" onsubmit="return confirmCancel();">
                                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($rent['booking_id']) ?>">
                                    <input type="hidden" name="car_id" value="<?= htmlspecialchars($rent['car_id']) ?>">
                                    <button type="submit" class="cancel-btn">Cancel Booking</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <p class="error-text">No approved bookings found.</p>
        <?php endif; ?>
    </div>

    <script>
        function confirmCancel() {
            return confirm("Are you sure you want to cancel this booking?");
        }
    </script>
</body>

</html>

<?php include "../includes/footer-2.php"; ?>