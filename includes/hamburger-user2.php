<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Sidebar Menu</title>
    <link rel="stylesheet" href="../css/media-query.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <header class="hamburger-header-container">
        <div class="hamburger-menu-toggle-main" id="hamburger-icon-clickable">
            ☰
        </div>
        <div class="hamburger-logo">
            <a href="../index.php"><img src="../uploads/logo-transparent.svg" alt="AIM SWIFT Car Rentals"></a>
        </div>
    </header>

    <nav class="sidebar-navigation-drawer-main" id="sidebar-panel-drawer">
        <button class="sidebar-close-button-main" id="sidebar-close-button-clickable">&times;</button>
        <ul class="sidebar-navigation-links-listing">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../browse.php">Browse</a></li>
            <li><a href="../about.php">About</a></li>
            <li><a href="../contact.php">Contact</a></li>

            <?php if (isset($_SESSION['id'])): ?>
                <li><a href="../user_features/cancel_pending_rent.php">Pending Rent</a></li>
                <li><a href="../logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a></li>
            <?php else: ?>
                <li><a href="../login.php">Login</a></li>
                <li><a href="../signup.php">Signup</a></li>
            <?php endif; ?>
        </ul>
    </nav>

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