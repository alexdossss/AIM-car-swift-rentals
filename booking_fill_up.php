<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "connection.php";

    if (!isset($_GET['id'])) {
        die("Invalid car selection.");
    }

    $dashboard_url = "view_cars.php"; 
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'admin') {
            $dashboard_url = "dashboard_admin.php"; 
        } else {
            $dashboard_url = "dashboard_user.php"; 
        }
    }
    $car_id = $_GET['id'];
    $user_id = $_SESSION['id'];

    $query = $conn->prepare("SELECT * FROM cars WHERE id = :id");
    $query->bindParam(":id", $car_id, PDO::PARAM_INT);
    $query->execute();
    $car = $query->fetch(PDO::FETCH_ASSOC);

    $queryUser = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $queryUser->bindParam(":id", $user_id, PDO::PARAM_INT);
    $queryUser->execute();
    $user = $queryUser->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Car not found.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $days_booked = $_POST['days_booked'];
        $start_date = date("Y-m-d"); 
        $end_date = date("Y-m-d", strtotime("+$days_booked days"));
        $total_price = $car['price_per_day'] * $days_booked;
        $status = "pending";

        $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, status, total_price, created_at)
                                VALUES (:user_id, :car_id, :start_date, :end_date, :status, :total_price, NOW())");
        $stmt->execute([
            ":user_id" => $user_id,
            ":car_id" => $car_id,
            ":start_date" => $start_date,
            ":end_date" => $end_date,
            ":status" => $status,
            ":total_price" => $total_price
        ]);

        $to = $user['email'];
        $subject = "Confirm Your Car Rental Booking";
        $message = "Hello {$user['full_name']},\n\nClick the link below to confirm your car booking:\n\n"
                 . "http://localhost/aim_swift_car_rentals/confirm_booking.php?user_id=$user_id&car_id=$car_id";
        $headers = "From: noreply@aimswift.com";

        mail($to, $subject, $message, $headers);

        echo "<script>
            alert('Booking request sent! Please check your email to confirm.');
            window.location.href = '$dashboard_url';
          </script>";
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Fill Up</title>
    <script>
        function confirmBooking() {
            return confirm("Are you sure you want to book this car?");
        }
    </script>
</head>
<body>
    <h1>Booking Fill-Up Info</h1>
    <a href="<?= $dashboard_url ?>">Back to Dashboard</a>
    
    <h1><?= htmlspecialchars($car['model']) ?></h1>
    <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image" width="200">
    <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
    <p><strong>Brand:</strong> <?= htmlspecialchars($car['brand']) ?></p>
    <p><strong>Price per day:</strong> $<?= htmlspecialchars($car['price_per_day']) ?></p>

    <br>

    <form method="POST" onsubmit="return confirmBooking();">
        <h3>This Booking will be based on:</h3>
        <h2>Name: <?= htmlspecialchars($user['full_name']) ?></h2>
        <h2>Email: <?= htmlspecialchars($user['email']) ?></h2>

        <p><strong>Total Price:</strong> $<span id="total_price">0</span></p>

        <label for="days_booked">Enter Number Of Days: </label>
        <input type="number" id="days_booked" name="days_booked" min="2" required oninput="updateTotal()">
        <br>

        <button type="submit">Book Car</button>
    </form>

<script>
    function updateTotal() {
        let days = document.getElementById("days_booked").value;
        let pricePerDay = <?= json_encode($car['price_per_day']) ?>;
        let total = days * pricePerDay;

        document.getElementById("total_price").textContent = total ? total.toFixed(2) : "0";
    }
</script>
</body>
</html>
