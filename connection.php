<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
     

    $db_server = "mysql-db";
    $db_user = "aim-admin";
    $db_pass = "aim-password";
    $db_name = "car_rental";

    try {
        $conn = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>