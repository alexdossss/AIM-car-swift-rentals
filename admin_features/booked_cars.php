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
           (DATEDIFF(bh.booking_end_date, bh.booking_start_date) * c.price_per_day) AS total_price
    FROM booking_history bh
    JOIN cars c ON bh.car_id = c.id
    ")->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Cars</title>
</head>
<body>
    <h1>Booked Cars</h1>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>
    
    <table>
        <h1>Pending Rents</h1>
        <tr>
            <th></th>
            <th>Car Type</th>
            <th>Car ID</th>
            <th>Car Brand</th>
            <th>Car Model</th>
            <th>Year</th>
            <th>License Plate</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Days Left Before Rent Ends</th>
            <th>Price Per Day</th>
            <th>Total Price</th>
        </tr>
        <?php if (!empty($Booked_cars)): ?>
            <?php foreach ($Booked_cars as $booked): ?>
                <?php if ($booked['status'] === "not returned"): ?> 
                    <tr>
                        <td><img src="../display_image.php?id=<?= htmlspecialchars($booked['car_id']) ?>" alt="Car Image" width="200"></td>
                        <td><?= $booked['car_type'] ?></td>
                        <td><?= $booked['car_id'] ?></td>
                        <td><?= $booked['brand'] ?></td>
                        <td><?= $booked['model'] ?></td>
                        <td><?= $booked['year'] ?></td>
                        <td><?= $booked['license_plate'] ?></td>
                        <td><?= $booked['customer_name'] ?></td>
                        <td><?= $booked['customer_email'] ?></td>
                        <td><?= $booked['booking_start_date'] ?></td>
                        <td><?= $booked['booking_end_date'] ?></td>
                        <td>    
                            <?php
                                $end_date = new DateTime($booked['booking_end_date']); 
                                $today = new DateTime();
                                $days_left = $today->diff($end_date)->days; 

                                echo ($today > $end_date) ? "Expired" : $days_left . " days left";
                            ?>
                        </td>
                        <td><?= $booked['price_per_day'] ?></td>
                        <td><?= $booked['total_price'] ?></td>

                        <td>
                            <form action="return_car.php" method="POST">
                                <input type="hidden" name="booking_id" value="<?= $booked['id'] ?>">
                                <input type="hidden" name="car_id" value="<?= $booked['car_id'] ?>">
                                <button type="submit">Car is Returned</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="11" style="color: red; text-align: center;">No Cars are Currently Booked.</td>
            </tr>
        <?php endif; ?>
    </table>
    <br>

    <table>
        <h1>Rent History </h1>
        <tr>
            <th></th>
            <th>Car Type</th>
            <th>Car ID</th>
            <th>Car Brand</th>
            <th>Car Model</th>
            <th>Year</th>
            <th>License Plate</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Price Per Day</th>
            <th>Total Price</th>
        </tr>
        <?php if (!empty($Booked_cars)): ?>
            <?php foreach ($Booked_cars as $booked): ?>
                <?php if ($booked['status'] === "returned"): ?> 
                    <tr>
                        <td><img src="../display_image.php?id=<?= htmlspecialchars($booked['car_id']) ?>" alt="Car Image" width="200"></td>
                        <td><?= $booked['car_type'] ?></td>
                        <td><?= $booked['car_id'] ?></td>
                        <td><?= $booked['brand'] ?></td>
                        <td><?= $booked['model'] ?></td>
                        <td><?= $booked['year'] ?></td>
                        <td><?= $booked['license_plate'] ?></td>
                        <td><?= $booked['customer_name'] ?></td>
                        <td><?= $booked['customer_email'] ?></td>
                        <td><?= $booked['booking_start_date'] ?></td>
                        <td><?= $booked['booking_end_date'] ?></td>
                        <td><?= $booked['price_per_day'] ?></td>
                        <td><?= $booked['total_price'] ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
