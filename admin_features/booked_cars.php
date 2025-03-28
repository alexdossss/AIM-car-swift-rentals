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

$Booked_cars = $conn->query("
    SELECT bh.*, 
           c.price_per_day, 
           c.car_type,
           DATEDIFF(bh.booking_end_date, bh.booking_start_date) AS rental_days,
           (DATEDIFF(bh.booking_end_date, bh.booking_start_date) * c.price_per_day) AS total_price,
           bh.status
    FROM booking_history bh
    JOIN cars c ON bh.car_id = c.id
    ")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/media-query.css">
    <title>Booked Cars | AIM Swift Car Rentals</title>
</head>

<body>

    <div class="booked-cars-container">

        <!-- Desktop Header -->
        <div class="header-container">
            <?php include "../includes/header-admin2.php"; ?>
        </div>

        <!-- Mobile Header -->
        <div class="mobile-header">
            <?php include "../includes/hamburger-admin2.php"; ?>
        </div>

        <!-- Pending Rents Table -->
        <h1>Pending Rents</h1>
        <div class="booked-cars-wrapper">
            <table class="booked-cars-table">
                <tr class="booked-cars-header">
                    <th>Type</th>
                    <th>ID</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>License Plate</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days Left</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
                <?php
                $hasPendingRents = false;
                foreach ($Booked_cars as $booked):
                    if ($booked['status'] === "not returned"):
                        $hasPendingRents = true;
                ?>
                        <tr class="booked-cars-row">
                            <td class="booked-cars-data"><?= $booked['car_type'] ?></td>
                            <td class="booked-cars-data"><?= $booked['car_id'] ?></td>
                            <td class="booked-cars-data"><?= $booked['brand'] ?></td>
                            <td class="booked-cars-data"><?= $booked['model'] ?></td>
                            <td class="booked-cars-data"><?= $booked['year'] ?></td>
                            <td class="booked-cars-data"><?= $booked['license_plate'] ?></td>
                            <td class="booked-cars-data"><?= $booked['customer_name'] ?></td>
                            <td class="booked-cars-data"><?= $booked['customer_email'] ?></td>
                            <td class="booked-cars-data"><?= $booked['booking_start_date'] ?></td>
                            <td class="booked-cars-data"><?= $booked['booking_end_date'] ?></td>
                            <td class="booked-cars-data">
                                <?php
                                $end_date = new DateTime($booked['booking_end_date']);
                                $today = new DateTime();
                                $days_left = $today->diff($end_date)->days;

                                echo ($today > $end_date) ? "Expired" : $days_left . " days left";
                                ?>
                            </td>
                            <td class="booked-cars-data"><?= $booked['total_price'] ?></td>
                            <td class="booked-cars-action">
                                <form action="return_car.php" method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $booked['id'] ?>">
                                    <input type="hidden" name="car_id" value="<?= $booked['car_id'] ?>">
                                    <button class="booked-cars-btn" type="submit">Car Returned</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    endif;
                endforeach;
                if (!$hasPendingRents):
                    ?>
                    <tr>
                        <td colspan="13" style="color: red; text-align: center;">No Cars are Currently Booked.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Rent History Table -->
        <h1>Rent History</h1>
        <div class="booked-cars-wrapper">
            <table class="booked-cars-table">
                <tr class="booked-cars-header">
                    <th>Type</th>
                    <th>ID</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>License Plate</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                </tr>
                <?php
                $hasRentHistory = false;
                foreach ($Booked_cars as $booked):
                    if ($booked['status'] === "returned"):
                        $hasRentHistory = true;
                ?>
                        <tr class="booked-cars-row">
                            <td class="booked-cars-data"><?= $booked['car_type'] ?></td>
                            <td class="booked-cars-data"><?= $booked['car_id'] ?></td>
                            <td class="booked-cars-data"><?= $booked['brand'] ?></td>
                            <td class="booked-cars-data"><?= $booked['model'] ?></td>
                            <td class="booked-cars-data"><?= $booked['year'] ?></td>
                            <td class="booked-cars-data"><?= $booked['license_plate'] ?></td>
                            <td class="booked-cars-data"><?= $booked['customer_name'] ?></td>
                            <td class="booked-cars-data"><?= $booked['customer_email'] ?></td>
                            <td class="booked-cars-data"><?= $booked['booking_start_date'] ?></td>
                            <td class="booked-cars-data"><?= $booked['booking_end_date'] ?></td>
                            <td class="booked-cars-data"><?= $booked['total_price'] ?></td>
                        </tr>
                    <?php
                    endif;
                endforeach;
                if (!$hasRentHistory):
                    ?>
                    <tr>
                        <td colspan="11" style="color: red; text-align: center;">No Rent History Available.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <?php include '../includes/footer-2.php'; ?>
</body>

</html>