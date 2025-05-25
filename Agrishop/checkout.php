<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['farmer_id'])) {
    header('Location: login.php');
    exit;
}

$farmer_id = $_SESSION['farmer_id'];

// Fetch cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE farmer_id = :farmer_id");
$stmt->execute([':farmer_id' => $farmer_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    $_SESSION['cart_message'] = "Your cart is empty. Add some products first.";
    header('Location: cart.php');
    exit;
}

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += floatval($item['product_price']) * intval($item['quantity']);
}

$order_success = false;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');

    if (!$name || !$phone || !$address || !$pincode) {
        $error_message = "Please fill in all required fields.";
    } elseif (!preg_match('/^\d{6}$/', $pincode)) {
        $error_message = "Please enter a valid 6-digit pin code.";
    } else {
        // Create orders table if not exists (optional, run this separately ideally)
        /*
        CREATE TABLE IF NOT EXISTS orders (
            order_id INT AUTO_INCREMENT PRIMARY KEY,
            farmer_id INT,
            name VARCHAR(255),
            phone VARCHAR(20),
            address TEXT,
            pincode VARCHAR(10),
            total_price DECIMAL(10,2),
            order_date DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS order_items (
            order_item_id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT,
            product_id INT,
            product_name VARCHAR(255),
            product_price DECIMAL(10,2),
            quantity INT
        );
        */

        // Insert order details
        $stmt = $conn->prepare("INSERT INTO orders (farmer_id, name, phone, address, pincode, total_price) VALUES (:farmer_id, :name, :phone, :address, :pincode, :total_price)");
        $stmt->execute([
            ':farmer_id' => $farmer_id,
            ':name' => $name,
            ':phone' => $phone,
            ':address' => $address,
            ':pincode' => $pincode,
            ':total_price' => $total_price
        ]);
        $order_id = $conn->lastInsertId();

        // Insert order items
        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity) VALUES (:order_id, :product_id, :product_name, :product_price, :quantity)");

        foreach ($cart_items as $item) {
            $stmtItem->execute([
                ':order_id' => $order_id,
                ':product_id' => $item['product_id'],
                ':product_name' => $item['product_name'],
                ':product_price' => $item['product_price'],
                ':quantity' => $item['quantity'],
            ]);
        }

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE farmer_id = :farmer_id");
        $stmt->execute([':farmer_id' => $farmer_id]);

        $order_success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - AgroShop</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        h2 { color: #28a745; text-align: center; }
        .summary-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; font-size: 1.2em; text-align: right; margin-top: 10px; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; font-weight: 600; }
        input[type=text], textarea {
            width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;
            box-sizing: border-box;
        }
        textarea { resize: vertical; }
        .btn {
            margin-top: 20px;
            background-color: #28a745; color: white;
            border: none; padding: 12px 20px; cursor: pointer;
            font-size: 1em; border-radius: 5px;
            width: 100%;
        }
        .btn:hover { background-color: #218838; }
        .message { padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-top: 15px; text-align: center;}
        .error { padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 15px; text-align: center;}
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
    <h2>Checkout</h2>

    <?php if ($order_success): ?>
        <script>
    alert("🎉 Order placed successfully! Thank you for shopping with us.");
    window.location.href = "index.php"; // Redirect after alert
</script>

    <?php else: ?>

        <?php if ($error_message): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <h3>Order Summary</h3>
        <?php foreach ($cart_items as $item): ?>
            <div class="summary-item">
                <div><?= htmlspecialchars($item['product_name']) ?> (x<?= intval($item['quantity']) ?>)</div>
                <div>₹<?= number_format(floatval($item['product_price']) * intval($item['quantity']), 2) ?></div>
            </div>
        <?php endforeach; ?>
        <div class="total">Total Price: ₹<?= number_format($total_price, 2) ?></div>

        <form method="post">
            <label for="name">Full Name*</label>
            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

            <label for="phone">Phone Number*</label>
            <input type="text" id="phone" name="phone" required pattern="\d{10}" title="Enter 10-digit phone number" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

            <label for="address">Delivery Address*</label>
            <textarea id="address" name="address" required rows="3"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>

            <label for="pincode">Pin Code*</label>
            <input type="text" id="pincode" name="pincode" required pattern="\d{6}" title="Enter 6-digit pin code" value="<?= htmlspecialchars($_POST['pincode'] ?? '') ?>">

            <label>Payment Method</label>
            <input type="text" value="Cash on Delivery" readonly style="background:#eee; padding:8px; border-radius:4px; border:1px solid #ccc; width: 100%; margin-top:5px;">

            <button type="submit" class="btn">Place Order</button>
        </form>

    <?php endif; ?>
</div>
</body>
</html>
