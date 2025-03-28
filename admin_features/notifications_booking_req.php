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

$bookings = $conn->query("
    SELECT bookings.*, users.full_name, cars.brand, cars.model, cars.license_plate
    FROM bookings 
    JOIN users ON bookings.user_id = users.id 
    JOIN cars ON bookings.car_id = cars.id
    WHERE bookings.status = 'approved'
    ")->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];
    $stmt = $conn->prepare("
            SELECT users.email, users.full_name, users.phone, bookings.car_id, bookings.start_date, bookings.end_date, 
                   cars.brand, cars.model, cars.year, cars.license_plate
            FROM users 
            JOIN bookings ON users.id = bookings.user_id 
            JOIN cars ON bookings.car_id = cars.id
            WHERE bookings.id = :booking_id
        ");
    $stmt->execute([":booking_id" => $booking_id]);
    $booking_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking_data) {
        $user_email = $booking_data['email'];
        $user_name = $booking_data['full_name'];
        $user_phone = $booking_data['phone'];
        $car_id = $booking_data['car_id'];
        $brand = $booking_data['brand'];
        $model = $booking_data['model'];
        $year = $booking_data['year'];
        $license_plate = $booking_data['license_plate'];
        $start_date = $booking_data['start_date'];
        $end_date = $booking_data['end_date'];

        if ($action === "approve") {

            $stmt = $conn->prepare("UPDATE bookings SET status = 'approved' WHERE id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);

            $stmt = $conn->prepare("UPDATE cars SET status = 'booked' WHERE id = :car_id");
            $stmt->execute([":car_id" => $car_id]);
            $stmt = $conn->prepare("
                    INSERT INTO booking_history (car_id, brand, model, year, license_plate, customer_name, customer_email, customer_phone, booking_start_date, booking_end_date)
                    VALUES (:car_id, :brand, :model, :year, :license_plate, :customer_name, :customer_email, :customer_phone, :booking_start_date, :booking_end_date)
                ");
            $stmt->execute([
                ":car_id" => $car_id,
                ":brand" => $brand,
                ":model" => $model,
                ":year" => $year,
                ":license_plate" => $license_plate,
                ":customer_name" => $user_name,
                ":customer_email" => $user_email,
                ":customer_phone" => $user_phone,
                ":booking_start_date" => $start_date,
                ":booking_end_date" => $end_date
            ]);

            $stmt = $conn->prepare("DELETE FROM bookings WHERE id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
        } else {
            $stmt = $conn->prepare("DELETE FROM bookings WHERE id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
        }

        if ($user_email) {
            $subject = "Car Rental Booking " . ucfirst($action);
            $message = "Your booking has been $action by the admin.";
            $headers = "From: noreply@aimswift.com";

            if (!mail($user_email, $subject, $message, $headers)) {
                error_log("Mail failed to send to $user_email");
            }
        }

        echo "<script>alert('Booking request $action! Email sent to user.');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Booking Requests | AIM Swift Car Rentals</title>
    <link rel="icon" href="../uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>

    <div class="booking-requests-container">

        <!-- Desktop Header -->
        <div class="header-container">
            <?php include "../includes/header-admin2.php"; ?>
        </div>

        <!-- Mobile Header -->
        <div class="mobile-header">
            <?php include "../includes/hamburger-admin2.php"; ?>
        </div>

        <h1 class="booking-requests-title">Car Booking Requests</h1>

        <div class="booking-table-wrapper">
            <table class="booking-table">
                <tr>
                    <th>User Name</th>
                    <th>Image</th>
                    <th>Car Brand</th>
                    <th>Car Model</th>
                    <th>License Plate</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= $booking['full_name'] ?></td>
                        <td class="car-image-cell">
                            <img src="../display_image.php?id=<?= htmlspecialchars($booking['car_id']) ?>"
                                alt="Car Image" class="car-thumbnail">
                        </td>
                        <td><?= $booking['brand'] ?></td>
                        <td><?= $booking['model'] ?></td>
                        <td><?= $booking['license_plate'] ?></td>
                        <td><?= $booking['start_date'] ?></td>
                        <td><?= $booking['end_date'] ?></td>
                        <td>
                            <form method="POST" class="action-form">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                                <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <?php if (empty($bookings)): ?>
                <p class="no-bookings-msg">No Bookings Request Yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include "../includes/footer-2.php"; ?>

</body>

</html>