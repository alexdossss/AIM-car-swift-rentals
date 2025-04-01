<?php
$to = "test@example.com";
$subject = "MailHog Test";
$message = "This is a test email.";
$headers = "From: noreply@yourapp.com";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Email sent!";
} else {
    echo "❌ Failed to send email. Check logs.";
}
?>
