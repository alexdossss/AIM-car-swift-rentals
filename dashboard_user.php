<?php 
    session_start(); 
    include "connection.php";
    include "functions.php";

    $user_data = check_login($conn);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
    <h1>Home Page USER</h1>
    <h2><?= $_SESSION['full_name']?></h2>
    <?php
    if(check_login($conn)){
        include "header.php";
    };
    ?>
    <a href="./user_feartures/cancel_pending_rent.php">Pending Rent</a>
    <?php include_once "view_cars.php" ?>
</body>
</html>