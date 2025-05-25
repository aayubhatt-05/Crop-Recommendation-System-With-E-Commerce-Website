<?php require_once 'config.php'; 

// Registration
if(isset($_POST['register'])) {
    $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $farm_name = filter_var($_POST['farm_name'], FILTER_SANITIZE_STRING);
    $farm_location = filter_var($_POST['farm_location'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }
    if($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }
    if(strlen($password) < 8) {
        echo "Password must be at least 8 characters long!";
        exit;
    }

    // Check for existing email
    $stmt = $conn->prepare("SELECT COUNT(*) FROM farmers WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if($stmt->fetchColumn() > 0) {
        echo "Email already registered!";
        exit;
    }

    // Insert farmer
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO farmers (fullname, email, phone, farm_name, farm_location, password) 
                           VALUES (:fullname, :email, :phone, :farm_name, :farm_location, :password)");
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':farm_name', $farm_name);
    $stmt->bindParam(':farm_location', $farm_location);
    $stmt->bindParam(':password', $hashed_password);

    if($stmt->execute()) {
        header("Location: login.php?registered=success");
        exit;
    } else {
        echo "Registration failed!";
    }
}

// Login
if(isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM farmers WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $farmer = $stmt->fetch(PDO::FETCH_ASSOC);

    if($farmer && password_verify($password, $farmer['password'])) {
        $_SESSION['user'] = $farmer['email'];
        $_SESSION['farmer_id'] = $farmer['id'];
        $_SESSION['farmer_name'] = $farmer['fullname']; // Store full name
        header("Location: index.php");
        exit;
    } else {
        echo "Invalid credentials!";
    }
}

// Add to Cart
// Add to Cart
if (isset($_POST['add_to_cart']) && isset($_SESSION['farmer_id'])) {
    $farmer_id = $_SESSION['farmer_id'];
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'] ?? 1, FILTER_SANITIZE_NUMBER_INT);

    // Fetch product data
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Invalid product!";
        exit;
    }

    // Check if already in cart
    $stmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE farmer_id = :farmer_id AND product_id = :product_id");
    $stmt->bindParam(':farmer_id', $farmer_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Update quantity
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE cart_id = :cart_id");
        $stmt->bindParam(':quantity', $new_quantity);
        $stmt->bindParam(':cart_id', $cart_item['cart_id']);
    } else {
        // Insert with full product data
        $stmt = $conn->prepare("INSERT INTO cart (farmer_id, product_id, product_name, product_price, product_image, quantity) 
                                VALUES (:farmer_id, :product_id, :product_name, :product_price, :product_image, :quantity)");
        $stmt->bindParam(':farmer_id', $farmer_id);
        $stmt->bindParam(':product_id', $product['id']);
        $stmt->bindParam(':product_name', $product['name']);
        $stmt->bindParam(':product_price', $product['price']);
        $stmt->bindParam(':product_image', $product['image']);
        $stmt->bindParam(':quantity', $quantity);
    }

    if ($stmt->execute()) {
        echo "Product added to cart!";
    } else {
        echo "Failed to add to cart!";
    }
}


// Add to Wishlist
if(isset($_POST['add_to_wishlist']) && isset($_SESSION['farmer_id'])) {
    $farmer_id = $_SESSION['farmer_id'];
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if product exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    if(!$stmt->fetch()) {
        echo "Invalid product!";
        exit;
    }

    // Check if already in wishlist
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE farmer_id = :farmer_id AND product_id = :product_id");
    $stmt->bindParam(':farmer_id', $farmer_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    if($stmt->fetch()) {
        echo "Product already in wishlist!";
        exit;
    }

    // Insert new item
    $stmt = $conn->prepare("INSERT INTO wishlist (farmer_id, product_id) VALUES (:farmer_id, :product_id)");
    $stmt->bindParam(':farmer_id', $farmer_id);
    $stmt->bindParam(':product_id', $product_id);

    if($stmt->execute()) {
        echo "Product added to wishlist!";
    } else {
        echo "Failed to add to wishlist!";
    }
    
// // Remove from Cart
// if(isset($_POST['remove_from_cart'])) {
//     // Check if user is logged in
//     if(!isset($_SESSION['farmer_id'])) {
//         error_log("Remove failed: No farmer_id in session");
//         echo "Error: You must be logged in to remove items from the cart.";
//         ob_end_flush();
//         exit;
//     }

//     $cart_id = filter_var($_POST['cart_id'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
//     $farmer_id = $_SESSION['farmer_id'];

//     error_log("Remove attempt: cart_id=$cart_id, farmer_id=$farmer_id");

//     if($cart_id <= 0) {
//         error_log("Invalid cart_id: $cart_id");
//         echo "Error: Invalid cart item ID.";
//         ob_end_flush();
//         exit;
//     }

//     try {
//         // Verify the cart item exists and belongs to the farmer
//         $check_stmt = $conn->prepare("SELECT cart_id, farmer_id, product_id FROM cart WHERE id = :id AND farmer_id = :farmer_id");
//         $check_stmt->bindParam(':id', $cart_id, PDO::PARAM_INT);
//         $check_stmt->bindParam(':farmer_id', $farmer_id, PDO::PARAM_INT);
//         $check_stmt->execute();
//         $cart_row = $check_stmt->fetch(PDO::FETCH_ASSOC);

//         if(!$cart_row) {
//             error_log("Cart item not found: cart_id=$cart_id, farmer_id=$farmer_id");
//             echo "Error: Cart item not found or does not belong to you.";
//             ob_end_flush();
//             exit;
//         }

//         error_log("Cart item found: " . json_encode($cart_row));

//         // Delete the cart item
//         $delete_stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = :id AND farmer_id = :farmer_id");
//         $delete_stmt->bindParam(':id', $cart_id, PDO::PARAM_INT);
//         $delete_stmt->bindParam(':farmer_id', $farmer_id, PDO::PARAM_INT);
//         $result = $delete_stmt->execute();

//         error_log("Delete executed: result=" . ($result ? 'true' : 'false') . ", rows affected=" . $delete_stmt->rowCount());

//         if($result && $delete_stmt->rowCount() > 0) {
//             error_log("Cart item removed successfully: cart_id=$cart_id");
//             $_SESSION['cart_message'] = "Product removed from cart successfully!";
//             header("Location: cart.php");
//             ob_end_flush();
//             exit;
//         } else {
//             error_log("Delete failed: no rows affected for cart_id=$cart_id");
//             echo "Error: Failed to remove item from the database.";
//             ob_end_flush();
//             exit;
//         }
//     } catch(PDOException $e) {
//         error_log("Database error during cart removal: " . $e->getMessage());
//         echo "Error: Database error occurred: " . $e->getMessage();
//         ob_end_flush();
//         exit;
//     }
// } else {
//     error_log("Invalid remove_from_cart request");
//     echo "Error: Invalid request.";
//     ob_end_flush();   
//     exit;
// }


// Remove from Wishlist
if(isset($_POST['remove_from_wishlist']) && isset($_SESSION['farmer_id'])) {
    $wishlist_id = filter_var($_POST['wishlist_id'], FILTER_SANITIZE_NUMBER_INT);
    $farmer_id = $_SESSION['farmer_id'];

    $stmt = $conn->prepare("DELETE FROM wishlist WHERE id = :id AND farmer_id = :farmer_id");
    $stmt->bindParam(':id', $wishlist_id);
    $stmt->bindParam(':farmer_id', $farmer_id);

    if($stmt->execute()) {
        header("Location: wishlist.php");
        exit;
    } else {
        echo "Failed to remove item!";
    }
}
}
?>
