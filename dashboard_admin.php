<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include "connection.php";
include "functions.php";

$user_data = check_login($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/media-query.css">
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <title>Admin Dashboard | AIM Swift Car Rentals</title>
</head>
<body>

    <!-- Desktop Header -->
    <div class="header-container">
        <?php include "includes/header-admin.php"; ?> 
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <?php include "includes/hamburger-admin.php"; ?>  
    </div>
    
    <div class="admin-dashboard">
        <div id="main-container">
            <?php include_once "view_cars.php"; ?>
        </div>
    </div>
</body>

<?php include 'includes/footer.php'?>

</html>
