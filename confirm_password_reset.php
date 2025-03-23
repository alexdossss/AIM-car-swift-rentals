<?php
    session_start();
    include "connection.php";

    if (!isset($_GET['token'])) {
        die("Invalid request.");
    }

    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT user_id, email FROM password_reset_requests WHERE token = :token LIMIT 1");
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->execute();
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $resetRequest["email"];
    if (!$resetRequest) {
        die("Invalid or expired token.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_password"])) {
        $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

        $updatePassword = $conn->prepare("UPDATE users SET password_hash = :password WHERE id = :user_id");
        $updatePassword->bindParam(":password", $new_password);
        $updatePassword->bindParam(":user_id", $resetRequest["user_id"]);
        $updatePassword->execute();

        $deleteToken = $conn->prepare("DELETE FROM password_reset_requests WHERE token = :token");
        $deleteToken->bindParam(":token", $token);
        $deleteToken->execute();

        echo "<p style='color: green;'>Password successfully updated. You can now <a href='login.php'>log in</a>.</p>";
    }


?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
    </head>
    <body>
        <h1>Reset Password</h1>
        <h1>Account: <?= $email ?></h1>
        <form method="POST">
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit">Update Password</button>
        </form>
    </body>
</html>
