<?php
session_start();
include "connection.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["full_name"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["phone"])) {
        $message = "<p style='color: red;'>Invalid request.</p>";
    } else {
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
                $message = "<p style='color: red;'>Email is already registered and verified.</p>";
            } else {
                $query = $conn->prepare("INSERT INTO pending_users (full_name, email, password_hash, phone, verification_token, created_at) VALUES (:full_name, :email, :password, :phone, :verification_token, :created_at)");

                if ($query->execute([
                    'full_name' => $full_name,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                    'verification_token' => $verification_token,
                    'created_at' => $created_at
                ])) {
                    $subject = "Verify Your Email";
                    $verification_link = "http://localhost/aim_swift_car_rentals/verify_email.php?token=$verification_token";
                    $messageContent = "Hi $full_name,\n\nPlease verify your email by clicking the link below:\n\n$verification_link\n\nIf you didn't request this, you can ignore this email.";

                    $headers = "From: no-reply@carRental.com\r\n";
                    mail($email, $subject, $messageContent, $headers);

                    $message = "<p style='color: green;'>Please check your email to verify your account.</p>";
                } else {
                    $message = "<p style='color: red;'>Failed to register user.</p>";
                }
            }
        } catch (PDOException $e) {
            $message = "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/media-query.css">
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <title>Signup | AIM SWIFT Car Rentals</title>
</head>

<body>

    <div class="navbar">
        <div class="hamburger-menu" id="hamburger-icon-clickable">
            ☰
        </div>

        <div class="navbar-logo">
            <img src="uploads/logo-transparent.svg" alt="AIM SWIFT Logo">
        </div>

        <!-- Desktop navbar links -->
        <div class="navbar-links">
            <a href="index.php" class="navbar-link">HOME</a>
            <a href="login.php" class="navbar-link">LOGIN</a>
            <a href="signup.php" class="navbar-link">SIGN UP</a>
            <?php if (isset($_SESSION['id'])): ?>
                <a href="user_features/cancel_pending_rent.php" class="navbar-link">Pending Rent</a>
                <a href="logout.php" class="navbar-link" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-navigation-drawer-main" id="sidebar-panel-drawer">
        <button class="sidebar-close-button-main" id="sidebar-close-button-clickable">&times;</button>
        <ul class="sidebar-navigation-links-listing">
            <li><a href="index.php">HOME</a></li>
            <li><a href="login.php">LOGIN</a></li>
            <li><a href="signup.php">SIGN UP</a></li>
            <?php if (isset($_SESSION['id'])): ?>
                <li><a href="user_features/cancel_pending_rent.php">Pending Rent</a></li>
                <li><a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="login-text-container">
        <span class="login-text">Sign Up</span>
    </div>

    <!-- Signup Form -->
    <div class="signup-page">
        <div class="signup-form-container">
            <form action="signup.php" method="post">
                <input class="signup-input" type="text" name="full_name" placeholder="Full Name" required>
                <input class="signup-input" type="email" name="email" placeholder="Email" required>
                <input class="signup-input" type="password" name="password" placeholder="Password" required>
                <input class="signup-input" type="text" name="phone" placeholder="Phone" required>

                <button class="signup-button" type="submit">Register</button>

                <div class="signup-message">
                    <?php echo $message; ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.getElementById("hamburger-icon-clickable");
            const sidebar = document.getElementById("sidebar-panel-drawer");
            const closeBtn = document.getElementById("sidebar-close-button-clickable");

            hamburger.addEventListener("click", function() {
                sidebar.classList.add("active");
            });

            closeBtn.addEventListener("click", function() {
                sidebar.classList.remove("active");
            });

            document.addEventListener("click", function(event) {
                if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                    sidebar.classList.remove("active");
                }
            });
        });
    </script>

</body>

</html>