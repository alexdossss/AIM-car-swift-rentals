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
    <h1>Home Page ADMIN</h1>
    <h2><?= $_SESSION['full_name']?></h2>
    <?php
    if(check_login($conn)){
        include "header.php";
    };
    ?>
    <?php
     if( $_SESSION['role'] == "admin"){
        include "admin_feature_panel.php";
     }
    ?>
    <?php include_once "view_cars.php" ?>
</body>
</html>