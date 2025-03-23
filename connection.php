<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
     

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "car_rental";

    try {
        $conn = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>