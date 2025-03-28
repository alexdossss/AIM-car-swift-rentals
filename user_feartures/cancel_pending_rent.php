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

    if (!isset($_SESSION['id'])) {
        echo "You must be logged in to view this page.";
        exit();
    }

    $user_id = $_SESSION['id'];


    $query = $conn->prepare("
        SELECT bookings.id AS booking_id, bookings.user_id, bookings.car_id, bookings.start_date, bookings.end_date, 
            bookings.total_price, bookings.status, users.full_name, cars.brand, cars.model, cars.price_per_day
        FROM bookings
        INNER JOIN users ON bookings.user_id = users.id  
        INNER JOIN cars ON bookings.car_id = cars.id  
        WHERE bookings.user_id = :user_id AND bookings.status = 'approved'
    ");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $approvedBookings = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Bookings</title>
</head>
<body>
    <h1>Approved Bookings</h1>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>
    <h2><?= htmlspecialchars($_SESSION['full_name']) ?>'s Approved Car Rentals</h2>

    <?php if (!empty($approvedBookings)): ?>
        <table>
            <tr>
                <th>Car Brand</th>
                <th>Model</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Price Per Day</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
            <?php foreach ($approvedBookings as $rent): 
                $start_date = new DateTime($rent['start_date']);
                $end_date = new DateTime($rent['end_date']);
                $rental_days = $start_date->diff($end_date)->days;
                $price_per_day = ($rental_days > 0) ? $rent['total_price'] / $rental_days : $rent['total_price'];
            ?>
            <tr>
                <td><?= htmlspecialchars($rent['brand']) ?></td>
                <td><?= htmlspecialchars($rent['model']) ?></td>
                <td><?= htmlspecialchars($rent['start_date']) ?></td>
                <td><?= htmlspecialchars($rent['end_date']) ?></td>
                <td><?= htmlspecialchars($rent['price_per_day']) ?></td>
                <td><?= number_format($price_per_day, 2) ?></td>
                <td>
                    <form method="POST" action="cancel_booking.php" onsubmit="return confirmCancel();">
                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($rent['booking_id']) ?>">
                        <input type="hidden" name="car_id" value="<?= htmlspecialchars($rent['car_id']) ?>">
                        <button type="submit">Cancel Booking</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="color: red;">No approved bookings found.</p>
    <?php endif; ?>

    <script>
    function confirmCancel() {
        return confirm("Are you sure you want to cancel this booking?");
    }
    </script>

</body>
</html>
