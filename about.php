<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$noHeaderPages = ['login.php', 'signup.php'];
$bodyClass = in_array($currentPage, $noHeaderPages) ? '' : 'body-with-header';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | AIM Swift</title>
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <!-- Desktop Header -->
    <div class="header-container">
        <?php include "includes/header.php"; ?>
    </div>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <?php include "includes/hamburger-user.php"; ?>
    </div>

    <main class="about-container">

        <section class="about-section">
            <div class="about-content">
                <div class="about-text">
                    <h2>ABOUT US</h2>
                    <p>
                        At AIM Swift, we provide reliable, affordable, and hassle-free car rentals for every journey. Whether for business, travel, or daily use, our well-maintained fleet and easy booking process ensure a smooth experience. With competitive rates and excellent customer service, we make renting a car simple and stress-free. Book with us today and drive with confidence!
                    </p>
                </div>
                <div class="about-logo">
                    <img src="uploads/logo-about.svg" alt="AIM Swift Logo">
                </div>
            </div>
        </section>

        <section class="why-choose">
            <h2>Why Choose Us?</h2>
            <div class="choose-list">
                <div class="choose-item choose-left">Wide Selection of Vehicles - From compact cars to SUVs, we have the perfect ride for every occasion.</div>
                <div class="choose-item choose-right">Well-Maintained Fleet - Regularly serviced and insured vehicles for your safety.</div>
                <div class="choose-item choose-left">Affordable Rates - Competitive pricing with no hidden fees.</div>
                <div class="choose-item choose-right">New & Modern Vehicles - Drive the latest models with advanced features for comfort and safety.</div>
                <div class="choose-item choose-left">Easy Booking Process - Quick online reservations for your convenience.</div>
                <div class="choose-item choose-right">Unlimited Mileage Options - Enjoy the freedom to drive without worrying about mileage limits.</div>
            </div>
        </section>

        <section class="commitment">
            <h2>Our Commitment</h2>
            <p>
                We strive to deliver exceptional service by ensuring a seamless and stress-free rental experience from start to finish.
                Your satisfaction and safety are our top priorities, which is why we maintain a high-quality fleet, offer transparent pricing,
                and provide excellent customer support. Whether you're renting for a day, a week, or longer, we are committed to delivering
                reliability, comfort, and convenience every step of the way.
            </p>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>