<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    function check_login($conn)
    {
        if (isset($_SESSION["id"])) {
            $query = $conn->prepare("SELECT id, full_name FROM users WHERE id = :id");
            $query->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch(PDO::FETCH_ASSOC);
            }
        }
        header("Location: login.php");;
    }
?>
