<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriShop - Contact Us</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?> 

    <main>
        <section class="contact-container">
            <h2>Contact Us</h2>
            <p class="contact-subtitle">We’re here to help you grow! Reach out with any questions or feedback.</p>
            <div class="contact-info">
                <div class="contact-card">
                    <span class="contact-icon email-icon"></span>
                    <h3>Email</h3>
                    <p><a href="mailto:support@agrishop.com">support@agrishop.com</a></p>
                </div>
                <div class="contact-card">
                    <span class="contact-icon phone-icon"></span>
                    <h3>Phone</h3>
                    <p>+1-800-AGRISHOP (247-4746)</p>
                </div>
                <div class="contact-card">
                    <span class="contact-icon address-icon"></span>
                    <h3>Address</h3>
                    <p>123 Agri Lane, Farmville, AG 45678</p>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>