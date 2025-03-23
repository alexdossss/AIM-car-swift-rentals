<?php
    session_start(); 
    include "connection.php";

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST["email"])) {
            $error = "Invalid request.";
        } else {
            $email = $_POST["email"];
            $verification_token = bin2hex(random_bytes(32)); 

            try {
                $query = $conn->prepare("SELECT id, full_name FROM users WHERE email = :email");
                $query->bindParam(":email", $email, PDO::PARAM_STR);
                $query->execute();
                $user = $query->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    $error = "NO EMAIL REGISTERED.";
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

                    echo "<p style='color: green;'>Please check your email for the reset link.</p>";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
    </head>
    <body>
        <h1>Forgot Password</h1>
        <form method="POST">
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <input type="text" name="email" placeholder="Email" required>
            <button type="submit">Reset password</button>
        </form>
    </body>
</html>
