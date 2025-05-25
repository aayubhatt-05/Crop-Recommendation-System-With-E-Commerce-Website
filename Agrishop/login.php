<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriShop - Farmer Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'config.php'; ?>
<?php include 'header.php'; ?>


<main>
    <section class="login-container">
        <div class="login-box">
            <h2>Farmer Login</h2>
            <?php if(isset($_GET['registered']) && $_GET['registered'] === 'success'): ?>
                <p style="color: #4a773c; text-align: center;">Registration successful! Please login.</p>
            <?php endif; ?>
            <form action="process.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <button type="submit" name="login">Login</button>
                <div class="additional-links">
                    <a href="#">Forgot Password?</a>
                    <a href="register.php">Register as Farmer</a>
                </div>
            </form>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>