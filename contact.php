<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

$currentPage = basename($_SERVER['PHP_SELF']);
$noHeaderPages = ['login.php', 'signup.php'];
$bodyClass = in_array($currentPage, $noHeaderPages) ? '' : 'body-with-header';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST["phone"]);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        $error = "Please fill in all fields.";
    } else {
        $to = "aimswiftrentals@gmail.com";
        $headers = "From: $email\r\nReply-To: $email\r\n";
        $body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";

        if (mail($to, $subject, $body, $headers)) {
            $success = "Message sent successfully!";
        } else {
            $error = "Failed to send message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | AIM Swift</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="uploads/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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

    <div class="contact-container">
        <h2 class="contact-heading">Contact Us</h2>
        <p class="contact-description">Have questions? Feel free to reach out to us!</p>

        <div class="contact-content">
            <!-- Left Section: Contact Info -->
            <div class="contact-info-wrapper">
                <h3 class="contact-info-heading">Get in Touch</h3>
                <p class="contact-info-description">We're here to assist you. Contact us for inquiries, bookings, or any assistance needed.</p>

                <div class="contact-item">
                    <img src="uploads/location.svg" alt="Location Icon" class="contact-icon">
                    <p class="contact-info-text">#1 Holy Angel St, Angeles, Pampanga, 2009</p>
                </div>

                <div class="contact-item">
                    <img src="uploads/email.svg" alt="Email Icon" class="contact-icon">
                    <p class="contact-info-text">aimswiftrentals@gmail.com</p>
                </div>

                <div class="contact-item">
                    <img src="uploads/phone.svg" alt="Phone Icon" class="contact-icon">
                    <p class="contact-info-text">+63 931 203 3199</p>
                </div>
            </div>

            <!-- Right Section: Contact Form -->
            <div class="contact-form-wrapper">
                <div class="contact-form">
                    <form action="contact.php" method="POST">
                        <input type="text" name="name" placeholder="Your Name" required>
                        <input type="email" name="email" placeholder="Your Email" required>
                        <input type="text" name="phone" placeholder="Your Phone Number" required>
                        <input type="text" name="subject" placeholder="Subject" required>
                        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                        <button type="submit">SEND MESSAGE</button>
                        <div class="form-message">
                            <?php if (!empty($success)) {
                                echo "<p class='success'>$success</p>";
                            } ?>
                            <?php if (!empty($error)) {
                                echo "<p class='error'>$error</p>";
                            } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3056.906217072127!2d120.58948679864356!3d15.133037545151879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f24ec2f5a1f9%3A0x5e0af8a6aaab2282!2sHoly%20Angel%20University!5e0!3m2!1sen!2sph!4v1742750133033!5m2!1sen!2sph"
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?> 

</body>

</html>
