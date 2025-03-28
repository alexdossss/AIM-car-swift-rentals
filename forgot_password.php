<?php
session_start();
include "connection.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["email"])) {
        $error_message = "Invalid request.";
    } else {
        $email = $_POST["email"];
        $verification_token = bin2hex(random_bytes(32));

        try {
            $query = $conn->prepare("SELECT id, full_name FROM users WHERE email = :email");
            $query->bindParam(":email", $email, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $error_message = "No email registered.";
            } else {
                $insertToken = $conn->prepare("INSERT INTO password_reset_requests (user_id, email, token, created_at) 
                                                   VALUES (:user_id, :email, :token, NOW())");
                $insertToken->bindParam(":user_id", $user["id"]);
                $insertToken->bindParam(":email", $email);
                $insertToken->bindParam(":token", $verification_token);
                $insertToken->execute();

                $full_name = $user["full_name"];
                $reset_link = "http://localhost/aim_swift_car_rentals/confirm_password_reset.php?token=$verification_token";
                $subject = "Password Reset Request";
                $message = "Hi $full_name,\n\nIt looks like you requested a password reset. Click the link below to reset your password:\n\n$reset_link\n\nIf you didn't request this, you can ignore this email.";

                $headers = "From: no-reply@carRental.com\r\n";
                mail($email, $subject, $message, $headers);
            }
        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
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
    <title>Forgot Password | AIM SWIFT Car Rentals</title>
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
        <span class="login-text">Forgot Password</span>
    </div>

    <!-- Forgot Password Page Content -->
    <div class="forgotpassword-page">
        <div class="login-form-container">
            <form method="POST">
                <input class="login-email-input" type="email" name="email" placeholder="Enter your email" required>
                <button class="login-button" type="submit">Reset Password</button>

                <div class="success-message <?php echo isset($_POST['email']) && empty($error_message) ? 'active' : ''; ?>">
                    <?php if (isset($_POST['email']) && empty($error_message)) echo "Please check your email for the reset link."; ?>
                </div>

                <div class="error-message <?php echo !empty($error_message) ? 'active' : ''; ?>">
                    <?php echo $error_message; ?>
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