<?php require_once 'config.php';

// Fetch Categories
$categories = [
    'herbicides',
    'growth-promoters',
    'fungicides',
    'vegetables',
    'fruit-seeds',
    'farm-machinery',
    'nutrients',
    'flower-seeds',
    'insecticides',
    'organic-farming',
    'animal-husbandry'
];

// Fetch Products for Selected Category
$selected_category = isset($_GET['category']) ? filter_var($_GET['category'], FILTER_SANITIZE_STRING) : 'herbicides';
$stmt = $conn->prepare("SELECT * FROM products WHERE category = :category");
$stmt->bindParam(':category', $selected_category);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AgriShop - Products</title>
    <link rel="stylesheet" href="style.css">
    <style>
       .product-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: flex-start;
}

.product-card {
    flex: 1 1 calc(25% - 20px);
    box-sizing: border-box;
    background-color: #fff;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    max-width: calc(25% - 20px);
    display: flex;
    flex-direction: column;
    height: 400px; /* Fixed card height */
    justify-content: space-between;
}

.product-image {
    height: 180px; /* Fixed image container height */
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.product-info {
    margin-top: 10px;
    text-align: center;
}

.product-name {
    font-size: 16px;
    margin: 5px 0;
    min-height: 40px; /* Reserve space even if name is short */
}

.product-price {
    font-weight: bold;
    color: #1a6e1a;
}

.product-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    padding: 8px 10px;
    border: none;
    border-radius: 5px;
    background-color: #28a745;
    color: white;
    cursor: pointer;
    text-align: center;
}

.btn-wishlist {
    background-color: #ffc107;
}

@media screen and (max-width: 768px) {
    .product-card {
        flex: 1 1 calc(50% - 20px);
        max-width: calc(50% - 20px);
    }
}

@media screen and (max-width: 480px) {
    .product-card {
        flex: 1 1 100%;
        max-width: 100%;
    }
}

    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <main>
        <div class="products-container">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul class="category-list">
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="?category=<?= htmlspecialchars($category) ?>"
                               class="<?= $selected_category === $category ? 'active' : '' ?>">
                               <?= ucwords(str_replace('-', ' ', $category)) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <section class="product-grid">
                <?php if ($products): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="images/<?= htmlspecialchars($product['image']) ?>"
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                            </div>
                            <div class="product-actions">
                                <form action="process.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-cart">Add to Cart</button>
                                </form>
                                <form action="process.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <button type="submit" name="add_to_wishlist" class="btn btn-wishlist">Add to Wishlist</button>
                                </form>

                                
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products found in this category.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>

</html>
