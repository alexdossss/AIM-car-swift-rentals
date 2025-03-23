<?php
    session_start();
    include "connection.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST["email"]) || !isset($_POST["password"])) {
            echo "<p style='color: red;'>Invalid request.</p>";
            exit();
        }

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
                    echo "<p style='color: red;'>Incorrect password.</p>";
                }
            } else {
                echo "<p style='color: red;'>No account found with this email.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login | Manari </title>
</head>
<body>
    <h1>Login Page</h1>
    <a href="index.php">Home Page</a>
    <a href="signup.php">Signup</a>
    <form method="POST">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="forgot_password.php" target="_blank">Forgot Password</a>
</body>
</html>
