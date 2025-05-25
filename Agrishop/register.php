<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriShop - Farmer Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'config.php'; ?>
<?php include 'header.php'; ?>


    <main>
        <section class="register-container">
            <div class="register-box">
                <h2>Farmer Registration</h2>
                <form action="process.php" method="POST">
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="farm-name">Farm Name</label>
                        <input type="text" id="farm-name" name="farm_name" placeholder="Enter your farm name" required>
                    </div>
                    <div class="form-group">
                        <label for="farm-location">Farm Location</label>
                        <input type="text" id="farm-location" name="farm_location" placeholder="Enter farm location" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the Terms & Conditions</label>
                    </div>
                    <button type="submit" name="register">Register</button>
                    <div class="additional-links">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>