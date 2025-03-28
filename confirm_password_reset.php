<?php
session_start();
include "connection.php";

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

$stmt = $conn->prepare("SELECT user_id, email FROM password_reset_requests WHERE token = :token LIMIT 1");
$stmt->bindParam(":token", $token, PDO::PARAM_STR);
$stmt->execute();
$resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resetRequest) {
    die("Invalid or expired token.");
}

$email = $resetRequest["email"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_password"])) {
    $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

    $updatePassword = $conn->prepare("UPDATE users SET password_hash = :password WHERE id = :user_id");
    $updatePassword->bindParam(":password", $new_password);
    $updatePassword->bindParam(":user_id", $resetRequest["user_id"]);
    $updatePassword->execute();

    $deleteToken = $conn->prepare("DELETE FROM password_reset_requests WHERE token = :token");
    $deleteToken->bindParam(":token", $token);
    $deleteToken->execute();

    $message = "<p class='success-message active'>Password successfully updated. You can now <a href='login.php'>log in</a>.</p>";
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
    <title>Reset Password | AIM SWIFT Car Rentals</title>
</head>

<body>

    <div class="navbar">
        <!-- Hamburger icon for mobile -->
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

    <!-- Reset Password Page Content -->
    <div class="resetpassword-page">
        <div class="reset-password-form-container">
            <h3 class="account-email">Account: <?= htmlspecialchars($email) ?></h3>

            <form method="POST">
                <input class="reset-password-input" type="password" name="new_password" placeholder="New Password" required>
                <button class="reset-password-button" type="submit">Update Password</button>
                <?= $message ?>
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