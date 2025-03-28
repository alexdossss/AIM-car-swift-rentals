<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$noHeaderPages = ['login.php', 'signup.php'];
$bodyClass = in_array($currentPage, $noHeaderPages) ? '' : 'body-with-header';
?>


<body class="<?php echo $bodyClass; ?>">
    <header class="header-container">
        <div class="inner-header-container">
            <div class="logo">
                <a href="../index.php"><img src="../uploads/logo-transparent.svg" alt="AIM SWIFT Car Rentals" width="150"></a>
            </div>

            <nav class="nav-links">
                <a href="../index.php">HOME</a>
                <a href="../browse.php">BROWSE</a>
                <a href="../contact.php">CONTACT</a>
                <a href="../about.php">ABOUT</a>
                <?php if (isset($_SESSION['id'])): ?>
                    <a href="../user_feartures/cancel_pending_rent.php" class="pending-rent-btn">PENDING RENT</a>
                <?php endif; ?>
            </nav>

            <div class="auth-links">
                <?php if (isset($_SESSION['id'])): ?>
                    <a href="../logout.php" onclick="return confirm('Are you sure you want to log out?')">LOGOUT</a>
                <?php else: ?>
                    <a href="../login.php">LOG IN</a>
                    <a href="../signup.php">SIGN UP</a>
                <?php endif; ?>
            </div>
        </div>
    </header>