<?php
    session_start(); 
    include "connection.php";

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $stmt = $conn->prepare("SELECT * FROM pending_users WHERE verification_token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, phone, role, created_at) VALUES (:full_name, :email, :password, :phone, 'user', NOW())");
            $stmt->execute([
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'password' => $user['password_hash'],
                'phone' => $user['phone']
            ]);

            $stmt = $conn->prepare("DELETE FROM pending_users WHERE id = :id");
            $stmt->execute(['id' => $user['id']]);
            echo "Email verified. You can now log in.";
        } else {
            echo "Invalid or expired token.";
        }
    }
?>