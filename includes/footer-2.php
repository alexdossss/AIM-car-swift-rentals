<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$noFooterPages = ['login.php', 'signup.php'];
$footerClass = in_array($currentPage, $noFooterPages) ? '' : 'footer-with-content';
?>

<footer class="site-footer <?php echo $footerClass; ?>">
    <div class="footer-container">
        <div class="footer-column company-info">
            <img src="../uploads/footer-logo.svg" alt="AIM Swift Logo" class="footer-logo">
            <p class="footer-description">Bringing you smooth and swift rides, every time. Whether for a quick trip or a long journey, we've got the perfect car for you.</p>
        </div>
        <div class="footer-column contact-info">
            <h3 class="footer-heading">Get in Touch</h3>
            <p class="footer-address">
                <span><img src="../uploads/location.svg" alt="Location Icon" class="footer-icon"></span>
                #1 Holy Angel St, Angeles, 2009 Pampanga
            </p>
            <p class="footer-email">
                <span><img src="../uploads/email.svg" alt="Email Icon" class="footer-icon"></span>
                aimswiftrentals@gmail.com
            </p>
            <p class="footer-phone">
                <span><img src="../uploads/phone.svg" alt="Phone Icon" class="footer-icon"></span>
                +63 931 203 3199
            </p>
        </div>

        <div class="footer-column newsletter">
            <h3 class="footer-heading">Join Our Newsletter</h3>
            <p class="footer-text">Sign up to receive daily deals!</p>
            <form class="footer-form">
                <input type="email" class="footer-input" placeholder="Enter Your Email" required>
                <button type="submit" class="footer-button">Subscribe</button>
            </form>
        </div>
    </div>
</footer>

</body>

</html>