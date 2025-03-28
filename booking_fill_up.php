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
    $dashboard_url = $_SESSION['role'] === 'admin' ? "dashboard_admin.php" : "dashboard_user.php";
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
    $stmt->execute([":user_id" => $user_id, ":car_id" => $car_id, ":start_date" => $start_date, ":end_date" => $end_date, ":status" => $status, ":total_price" => $total_price]);

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
    <title>Booking Fill Up | AIM Swift Car Rental</title>
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/media-query.css">
    <script>
        function confirmBooking() {
            return confirm("Are you sure you want to book this car?");
        }
        function updateTotal() {
            let days = document.getElementById("days_booked").value;
            let pricePerDay = <?= json_encode($car['price_per_day']) ?>;
            let total = days * pricePerDay;
            document.getElementById("total_price").textContent = total ? total.toFixed(2) : "0";
        }
    </script>
</head>
<body>

    <!-- Desktop Header -->
    <div class="header-container">
        <?php 
        if (isset($_SESSION['id']) && $_SESSION['role'] === 'admin') {
            include 'includes/header-admin.php'; // Admin header
        } else {
            include 'includes/header.php'; // Regular user header
        }
        ?>
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <?php 
        if (isset($_SESSION['id']) && $_SESSION['role'] === 'admin') {
            include 'includes/hamburger-admin.php';
        } else {
            include 'includes/hamburger-user.php'; 
        }
        ?>
    </div>

    <div class="booking-wrapper">
        <h1 class="booking-title">Booking Details</h1> 

        <div class="car-display">
            <h2 class="car-title"><?= htmlspecialchars($car['year']) . " " . htmlspecialchars($car['brand']) . " " . htmlspecialchars($car['model']) ?></h2>
            <div class="car-image-container">
                <img src="display_image.php?id=<?= htmlspecialchars($car['id']) ?>" alt="Car Image">
            </div>
            <div class="car-specs">
                <p><strong>Type:</strong> <?= htmlspecialchars($car['car_type']) ?></p>
                <p><strong>Price per day:</strong> ₱<?= htmlspecialchars($car['price_per_day']) ?></p>
            </div>
        </div>

        <div class="reservation-panel">
            <form method="POST" onsubmit="return confirmBooking();">
                <h3 class="booking-header">Your Information</h3>
                <div class="customer-info">
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                </div>

                <div class="cost-summary">
                    <p><strong>Total Price:</strong> ₱<span id="total_price">0</span></p>
                </div>

                <div class="date-selection">
                    <label for="days_booked">Number of Days:</label>
                    <input type="number" id="days_booked" name="days_booked" min="2" required oninput="updateTotal()">
                </div>

                <div class="submit-section">
                    <button type="submit" class="book-car">Book Now</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
