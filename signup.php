<?php
    session_start();
    include "connection.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST["full_name"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["phone"])) {
            echo "<p style='color: red;'>Invalid request.</p>";
            exit();
        }

        $full_name = $_POST["full_name"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $phone = $_POST["phone"];
        $verification_token = bin2hex(random_bytes(32));
        $created_at = date("Y-m-d H:i:s");

        try {
            $checkQuery = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $checkQuery->execute(['email' => $email]);
            
            if ($checkQuery->rowCount() > 0) {
                echo "<p style='color: red;'>Email is already registered and verified.</p>";
                exit();
            }

            $query = $conn->prepare("INSERT INTO pending_users (full_name, email, password_hash, phone, verification_token, created_at) VALUES (:full_name, :email, :password, :phone, :verification_token, :created_at)");
            
            if ($query->execute([ 'full_name' => $full_name, 'email' => $email, 'password' => $password, 'phone' => $phone, 'verification_token' => $verification_token, 'created_at' => $created_at ])) {
                $subject = "Verify Your Email";
                $verification_link = "http://localhost/aim_swift_car_rentals/verify_email.php?token=$verification_token";
                $message = "Hi $full_name,\n\nPlease verify your email by clicking the link below:\n\n$verification_link\n\nIf you didn't request this, you can ignore this email.";
                
                $headers = "From: no-reply@carRental.com\r\n";
                mail($email, $subject, $message, $headers);
                
                echo "<p style='color: green;'>Please check your email to verify your account.</p>";
            } else {
                echo "<p style='color: red;'>Failed to register user.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Signup | Manari </title>
</head>
<body>
    <h1>Signup Page</h1>
    <a href="index.php">Home Page</a>
    <a href="login.php">Login</a>

    <form action="signup.php" method="post">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" required>
        <br>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" required>
        <br>
        <button type="submit">SIGN UP</button>
    </form>

</body>
</html>