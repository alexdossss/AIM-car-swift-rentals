<?php
session_start();
include "connection.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["email"]) || !isset($_POST["password"])) {
        $error_message = "Invalid request.";
    } else {
        $email = $_POST["email"];
        $password = $_POST["password"];

        try {
            $query = $conn->prepare("SELECT id, full_name, password_hash, role FROM users WHERE email = :email");
            $query->bindParam(":email", $email, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0) {
                $user = $query->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user["password_hash"])) {
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["full_name"] = $user["full_name"];
                    $_SESSION["role"] = $user["role"];

                    if ($user["role"] == "admin") {
                        header("Location: dashboard_admin.php");
                    } else {
                        header("Location: dashboard_user.php");
                    }
                    exit();
                } else {
                    $error_message = "Incorrect password.";
                }
            } else {
                $error_message = "No account found with this email.";
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
    <title>Login | AIM SWIFT Car Rentals</title>
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
        <span class="login-text">Login</span>
    </div>

    <!-- Main login page content -->
    <div class="login-page">
        <div class="login-form-container">
            <form method="POST">
                <input class="login-email-input" type="email" name="email" placeholder="Email" required>
                <input class="login-password-input" type="password" name="password" placeholder="Password" required>

                <a href="forgot_password.php" class="forgot-password-link">Forgot Password?</a>

                <button class="login-button" type="submit">Login</button>

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