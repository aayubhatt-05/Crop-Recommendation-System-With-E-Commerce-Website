<?php require_once 'config.php'; 

// Fetch Farmer Details
if(!isset($_SESSION['farmer_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT fullname, email, phone, farm_name, farm_location FROM farmers WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['farmer_id']);
$stmt->execute();
$farmer = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$farmer) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriShop - My Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>


    <main>
        <section class="account-container">
            <h2>My Account</h2>
            <div class="account-details">
                <h3>Farmer Details</h3>
                <p><strong>Full Name:</strong> <?= htmlspecialchars($farmer['fullname']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($farmer['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($farmer['phone']) ?></p>
                <p><strong>Farm Name:</strong> <?= htmlspecialchars($farmer['farm_name']) ?></p>
                <p><strong>Farm Location:</strong> <?= htmlspecialchars($farmer['farm_location']) ?></p>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>


    <script src="script.js"></script>
</body>
</html>