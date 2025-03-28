<?php
    session_start(); 
    include "connection.php";

    $imageSrc = "uploads/verified-green.svg"; 
    $message = "Email verified. You can now log in.";

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $stmt = $conn->prepare("SELECT * FROM pending_users WHERE verification_token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, phone, role, created_at) VALUES (:full_name, :email, :password, :phone, 'user', NOW())");
            $stmt->execute([
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'password' => $user['password_hash'],
                'phone' => $user['phone']
            ]);

            $stmt = $conn->prepare("DELETE FROM pending_users WHERE id = :id");
            $stmt->execute(['id' => $user['id']]);
        } else {
            $imageSrc = "uploads/invalid.svg"; 
            $message = "Invalid or expired token.";
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
    <title>Email Verification | AIM Swift Car Rentals</title>
</head>
<body>
    <div class="verify-container">
        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Verification Status" class="verify-image">
        <p class="verify-text"><?= htmlspecialchars($message) ?></p>
    </div>
</body>
</html>
