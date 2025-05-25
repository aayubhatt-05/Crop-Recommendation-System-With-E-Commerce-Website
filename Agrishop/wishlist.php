<?php require_once 'config.php'; 
// Fetch Wishlist Items
$wishlist_items = [];
if(isset($_SESSION['farmer_id'])) {
    $stmt = $conn->prepare("SELECT w.id, p.name, p.price, p.image 
                           FROM wishlist w 
                           JOIN products p ON w.product_id = p.id 
                           WHERE w.farmer_id = :farmer_id");
    $stmt->bindParam(':farmer_id', $_SESSION['farmer_id']);
    $stmt->execute();
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriShop - Wishlist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>


    <main>
        <section class="wishlist">
            <h2>Your Wishlist</h2>
            <?php if($wishlist_items): ?>
                <div class="wishlist-items">
                    <?php foreach($wishlist_items as $item): ?>
                        <div class="wishlist-item">
                            <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div>
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p>Price: $<?= number_format($item['price'], 2) ?></p>
                                <form action="process.php" method="POST">
                                    <input type="hidden" name="wishlist_id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="remove_from_wishlist">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Your wishlist is empty.</p>
            <?php endif; ?>
        </section>
    </main>
    <?php include 'footer.php'; ?>

</body>
</html>