<?php
require_once 'config.php';  // Starts session & creates $conn

// Redirect if not logged in
if (!isset($_SESSION['farmer_id'])) {
    header('Location: login.php');
    exit;
}

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_cart_id'])) {
    $cart_id = intval($_POST['remove_cart_id']);
    $farmer_id = $_SESSION['farmer_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = :cart_id AND farmer_id = :farmer_id");
    $stmt->execute([':cart_id' => $cart_id, ':farmer_id' => $farmer_id]);

    $_SESSION['cart_message'] = "Item removed from cart.";
    header("Location: cart.php");
    exit;
}

// Fetch cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE farmer_id = :farmer_id");
$stmt->execute([':farmer_id' => $_SESSION['farmer_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += floatval($item['product_price']) * intval($item['quantity']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Cart - AgroShop</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; margin: 0; padding: 0; }
        .container { max-width: 1000px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 10px; }
        h2 { text-align: center; color: #28a745; }
        .cart-item { display: flex; align-items: center; border-bottom: 1px solid #ddd; padding: 10px 0; }
        .cart-item img { width: 100px; height: 100px; object-fit: cover; border-radius: 5px; margin-right: 20px; }
        .cart-details { flex: 1; }
        .cart-details h3 { margin: 0 0 5px; }
        .cart-details p { margin: 2px 0; }
        .cart-item form { margin-left: 20px; }
        .remove-btn, .checkout-btn {
            background-color: #dc3545; color: white; border: none;
            padding: 8px 12px; border-radius: 5px; cursor: pointer;
        }
        .remove-btn:hover { background-color: #c82333; }
        .checkout-btn {
            background-color: #28a745; margin-top: 20px; float: right;
        }
        .checkout-btn:hover { background-color: #218838; }
        .total-price { font-weight: bold; margin-top: 20px; text-align: right; color: #333; }
        .message { text-align: center; color: green; margin-bottom: 10px; }
        .empty-cart { text-align: center; padding: 40px; color: #666; }
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
</head>
<body>

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

<div class="container">
    <h2>Your Cart</h2>

    <?php if (isset($_SESSION['cart_message'])): ?>
        <p class="message"><?= htmlspecialchars($_SESSION['cart_message']) ?></p>
        <?php unset($_SESSION['cart_message']); ?>
    <?php endif; ?>

    <?php if (!empty($cart_items)): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="images/<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" />
                <div class="cart-details">
                    <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                    <p>Price: ₹<?= number_format($item['product_price'], 2) ?></p>
                    <p>Quantity: <?= intval($item['quantity']) ?></p>
                </div>
                <form method="post" onsubmit="return confirm('Remove this item from cart?');">
                    <input type="hidden" name="remove_cart_id" value="<?= intval($item['cart_id']) ?>" />
                    <button type="submit" class="remove-btn">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="total-price">Total Price: ₹<?= number_format($total_price, 2) ?></div>
        <form action="checkout.php" method="post">
            <button type="submit" class="checkout-btn">Checkout / Buy Now</button>
        </form>

    <?php else: ?>
        <div class="empty-cart">
            <img src="images/cart-empty.png" alt="Empty Cart" style="width:200px;" /><br />
            Your cart is empty.
        </div>
    <?php endif; ?>
</div>
<br><br>
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>
