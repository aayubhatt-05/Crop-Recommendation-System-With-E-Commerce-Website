<?php
require_once 'config.php';

ob_start(); // Start output buffering

// Fetch featured products (example query, adjust as needed)
$featured_products = [];
$stmt = $conn->prepare("SELECT name, price, image FROM products LIMIT 3");
$stmt->execute();
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cart item count
$cart_items = [];
if(isset($_SESSION['farmer_id'])) {
    $stmt = $conn->prepare("SELECT c.cart_id FROM cart c WHERE c.farmer_id = :farmer_id");
    $stmt->bindParam(':farmer_id', $_SESSION['farmer_id'], PDO::PARAM_INT);
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agroshop - Empowering Farmers</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Inline CSS for a clean, green agricultural design */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .hero {
            background: url('images/hero-image.jpeg') no-repeat center center/cover;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
        }
        .hero h1 {
            font-size: 48px;
            margin: 0;
        }
        .hero p {
            font-size: 20px;
            margin: 10px 0;
        }
        .hero button {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s;
        }
        .hero button:hover {
            background: #218838;
        }
        .navbar {
            background: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .navbar .search {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .navbar .search input {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .navbar .search i {
            color: #28a745;
            cursor: pointer;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            justify-content: flex-end;
            margin: 0;
            padding: 0;
        }
        .navbar ul li {
            margin-left: 20px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .navbar ul li a:hover {
            color: #28a745;
        }
        .section {
            padding: 40px 20px;
            text-align: center;
        }
        .panels {
            display: flex;
            gap: 20px;
            padding: 20px;
        }
        .panel {
            flex: 1;
            height: 300px;
            background-size: cover;
            background-position: center;
            position: relative;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s;
        }
        .panel:hover {
            transform: scale(1.05);
        }
        .panel h2, .panel p {
            text-shadow: 1px 1px 2px black;
        }
        .panel button {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .panel button:hover {
            background: #218838;
        }
        .ecosystem-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .ecosystem-item {
            text-align: center;
            width: 150px;
        }
        .ecosystem-item i {
            font-size: 40px;
            color: #28a745;
            transition: transform 0.3s;
        }
        .ecosystem-item p {
            margin-top: 10px;
        }
        .ecosystem-item:hover i {
            transform: scale(1.1);
        }
        footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
        }
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }
        .social-icons a {
            color: #28a745;
            font-size: 24px;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #14524A;
        }
        .chat-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #FFC107;
            border-radius: 50%;
            padding: 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .chat-btn img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-btn span {
            color: #000000;
        }
        .chat-btn:hover {
            transform: scale(1.1);
            background-color: #FF9800;
        }
       

        /* Chat Modal Styles */
        #chat-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            position: relative;
        }
        .modal-content h2 {
            margin-top: 0;
        }
        .modal-content form {
            display: flex;
            flex-direction: column;
        }
        .modal-content input, .modal-content textarea {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #D3D3D3;
            border-radius: 5px;
        }
        .modal-content button {
            background-color: #1A3C34;
            color: #FFFFFF;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #14524A;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            .search {
                margin: 10px 0;
            }
            .menu-btn {
                margin-top: 10px;
            }
            .panel {
                height: 300px;
            }
            .icon-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .initiatives {
                flex-direction: column;
            }
            .initiatives .image, .initiatives .content {
                width: 100%;
            }
        }

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div>
            <h1>Empowering Farmers, Growing Together</h1>
            <p>Quality products for sustainable farming</p>
            <a href="products.php"><button>Shop Now</button></a>
        </div>
    </section>

    <!-- Interactive Panels -->
    <section class="panels">
        <div class="panel" style="background-image: url('images/contact-bg.webp');">
            <h2>Together</h2>
            <p>Empowering farmers with quality products</p>
        </div>
        <div class="panel" style="background-image: url('images/email-icon.webp');">
            <h2>For</h2>
            <p>Innovative solutions for sustainable farming</p>
        </div>
        <div class="panel" style="background-image: url('images/farm-field.jpg');">
            <h2>Greater Good</h2>
            <p>Supporting communities and the environment</p>
    </div>
    </section>

    <!-- Ecosystem Section -->
    <section class="section">
        <h2>Our Ecosystem</h2>
        <br><br>
        <div class="ecosystem-grid">
            <div class="ecosystem-item"><i class="fas fa-seedling"></i><p>Quality Seeds</p></div>
            <div class="ecosystem-item"><i class="fas fa-tools"></i><p>Farm Tools</p></div>
            <div class="ecosystem-item"><i class="fas fa-tractor"></i><p>Machinery</p></div>
            <div class="ecosystem-item"><i class="fas fa-water"></i><p>Irrigation</p></div>
            <div class="ecosystem-item"><i class="fas fa-leaf"></i><p>Fertilizers</p></div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section">
        <h2>About Agroshop</h2>
        <br><br>
        <p>Agroshop is dedicated to supporting farmers with top-quality agricultural products. Inspired by a legacy of empowerment, we bring innovation and sustainability to your fields.</p>
    </section>
    
  
    <!-- Footer -->
    <footer>
        <div class="chat-btn">
            <img src="images/cnt.jpeg" alt="Farmer">
            <span>Need Help? Chat with me!</span>
        </div>
    </footer>

    <!-- Chat Modal -->
    <div id="chat-modal">
    <div class="modal-content">
        <span class="close-btn">×</span>
        <h2>Contact Us</h2>
        <form action="send_email.php" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</div>

    <!-- JavaScript -->
    <script>
        // Open chat modal
        document.querySelector('.chat-btn').addEventListener('click', function() {
            document.getElementById('chat-modal').style.display = 'flex';
        });

        // Close chat modal
        document.querySelector('.close-btn').addEventListener('click', function() {
            document.getElementById('chat-modal').style.display = 'none';
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('chat-modal')) {
                document.getElementById('chat-modal').style.display = 'none';
            }
        });
    </script>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

</body>
</html>

<?php ob_end_flush(); // End output buffering ?>