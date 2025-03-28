<?php
    include "connection.php";

    $imageSrc = "uploads/verified-green.svg"; 
    $message = "Booking confirmed! The admin will now review your request.";

    if (!isset($_GET['user_id']) || !isset($_GET['car_id'])) {
        $imageSrc = "uploads/invalid.svg";
        $message = "Invalid confirmation link.";
    } else {
        $user_id = $_GET['user_id'];
        $car_id = $_GET['car_id'];

        $stmt = $conn->prepare("UPDATE bookings SET status = 'approved' WHERE user_id = :user_id AND car_id = :car_id AND status = 'pending'");
        $stmt->execute([":user_id" => $user_id, ":car_id" => $car_id]);

        if ($stmt->rowCount() === 0) {
            $imageSrc = "uploads/invalid.svg";
            $message = "No pending booking found or already confirmed.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <title>Booking Confirmation | AIM Swift Car Rentals</title>
</head>
<body>
    <div class="verify-container">
        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Booking Status" class="verify-image">
        <p class="verify-text"><?= htmlspecialchars($message) ?></p>
    </div>
</body>
</html>