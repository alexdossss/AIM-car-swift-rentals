<?php
session_start();
include "connection.php";
include "functions.php";


$query = $conn->prepare("SELECT * FROM cars LIMIT 3");
$query->execute();
$cars = $query->fetchAll(PDO::FETCH_ASSOC);

$isLoggedIn = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/media-query.css">
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Home | AIM Swift Car Rentals</title>
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

    <main>

        <section class="banner">
            <div class="banner-content">
                <a href="browse.php" class="book-now-btn">BOOK NOW</a>
            </div>
        </section>

        <!-- Most Popular Car Rent Deals Section -->
        <section class="popular-cars">
            <h2 class="section-title">MOST POPULAR CAR RENT DEALS</h2>
            <div class="cars-container">
                <div class="car-card">
                    <img src="uploads/vios.svg" alt="Car 1" class="car-img">
                    <div class="car-info">
                        <div class="car-rating">
                            <img src="uploads/ratings.svg" alt="star" class="star">
                        </div>
                        <div class="car-name">2022 Toyota Vios</div>
                        <ul class="car-description">
                            <li>5 Seater Sedan</li>
                            <li>Automatic Transmission</li>
                            <li>1.5-liter Gasoline Engine</li>
                        </ul>
                    </div>
                </div>

                <div class="car-card">
                    <img src="uploads/hiace.svg" alt="Car 2" class="car-img">
                    <div class="car-info">
                        <div class="car-rating">
                            <img src="uploads/ratings.svg" alt="star" class="star">
                        </div>
                        <div class="car-name">2020 Toyota Hiace</div>
                        <ul class="car-description">
                            <li>15 Seater Van</li>
                            <li>Automatic Transmission</li>
                            <li>2.8-liter Diesel Engine</li>
                        </ul>
                    </div>
                </div>

                <div class="car-card">
                    <img src="uploads/fortuner.svg" alt="Car 3" class="car-img">
                    <div class="car-info">
                        <div class="car-rating">
                            <img src="uploads/ratings.svg" alt="star" class="star">
                        </div>
                        <div class="car-name">2018 Toyota Fortuner</div>
                        <ul class="car-description">
                            <li>7 Seater SUV</li>
                            <li>Automatic Transmission</li>
                            <li>2.4-liter Diesel Engine</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials">
            <h3 class="testimonials-header">TESTIMONIALS</h3>
            <h2 class="testimonials-subheader">WHAT PEOPLE SAY ABOUT US?</h2>
            <p class="testimonial-text">“Renting a car has never been this easy! The service was professional, the staff was friendly, and the car was in perfect condition. Everything was transparent with no hidden fees, making the experience truly stress-free.”</p>
            <div class="testimonial-rating">
                <img src="uploads/ratings.svg" alt="star" class="testimonial-star">
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="how-it-works">
            <h2 class="how-it-works-header">HOW IT WORKS</h2>
            <h3 class="how-it-works-subheader">Rent with following 3 working steps</h3>

            <div class="steps-container">
                <!-- Step 1 -->
                <div class="step">
                    <img src="uploads/step1.svg" alt="Choose Your Car" class="step-image">
                    <h4 class="step-title">Choose Your Car</h4>
                    <p class="step-description">Browse our selection of vehicles. Pick the perfect ride for your trip.</p>
                </div>

                <!-- Step 2 -->
                <div class="step">
                    <img src="uploads/step2.svg" alt="Book Your Car" class="step-image">
                    <h4 class="step-title">Book & Confirm</h4>
                    <p class="step-description">Select your dates, complete the booking, and get instant confirmation.</p>
                </div>

                <!-- Step 3 -->
                <div class="step">
                    <img src="uploads/step3.svg" alt="Drive and Enjoy" class="step-image">
                    <h4 class="step-title">Pick Up & Drive</h4>
                    <p class="step-description">Pick up your car, enjoy your journey, and return it hassle-free when you're done!</p>
                </div>
            </div>
        </section>

        <?php include 'includes/footer.php' ?>

        <script>
            function showLoginAlert(event) {
                event.preventDefault();
                alert("You must log in or register to view/book this car.");
                window.location.href = "login.php";
                return false;
            }

        </script>
</body>
</html>