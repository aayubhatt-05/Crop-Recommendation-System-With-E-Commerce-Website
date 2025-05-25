<?php
// header.php - expects session started and $conn available

// Fetch cart items count if logged in
if (isset($_SESSION['farmer_id'])) {
    $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE farmer_id = :farmer_id");
    $stmt->bindParam(':farmer_id', $_SESSION['farmer_id'], PDO::PARAM_INT);
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $cart_items = [];
}
?>
<style>
    /* Header Styles */
    header {
        background: #fff;
        padding: 10px 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }
    .logo {
        font-size: 24px;
        font-weight: bold;
        color: #28a745;
    }
    .nav-links {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
    }
    .nav-links li {
        margin-left: 20px;
    }
    .nav-links a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }
    .nav-links a:hover {
        color: #28a745;
    }
    .account-menu {
        position: relative;
    }
    .account-dropdown {
        display: none;
        position: absolute;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        top: 100%;
        right: 0;
        width: 150px;
    }
    .account-menu:hover .account-dropdown {
        display: block;
    }
    .account-dropdown a {
        display: block;
        padding: 10px;
        color: #333;
        text-decoration: none;
    }
    .account-dropdown a:hover {
        background: #f8f9fa;
    }
</style>

<header>
    <nav class="navbar">
        <div class="logo">Agroshop</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="app.php">AI Crop Recommendation</a></li>

            <?php if (isset($_SESSION['farmer_id'])): ?>
                <li class="account-menu">
                    <a href="account.php" class="account-link">
                        Welcome, <?= htmlspecialchars($_SESSION['farmer_name'] ?? 'User') ?>
                    </a>
                    <div class="account-dropdown">
                        <a href="account.php">My Account</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="login.php">Farmer Login</a></li>
            <?php endif; ?>

            <li>
                <a href="cart.php">
                    Cart<?php if ($cart_items) echo " (" . count($cart_items) . ")"; ?>
                </a>
            </li>
            <li><a href="wishlist.php">Wishlist</a></li>
        </ul>
    </nav>
</header>
