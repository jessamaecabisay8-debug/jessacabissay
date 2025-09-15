<?php
session_start();
require_once "db.php";

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ==========================
   ADD PRODUCT TO CART
========================== */
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);

    // Fetch product from database
    $query = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
    $query->bind_param("i", $product_id);
    $query->execute();
    $result = $query->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // If product already exists in cart, increment quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            // Add new product to cart
            $_SESSION['cart'][$product_id] = [
                "id" => $product['id'],
                "name" => $product['name'],
                "price" => $product['price'],
                "image" => $product['image'],
                "quantity" => 1
            ];
        }
    }

    header("Location: cart.php");
    exit;
}

/* ==========================
   UPDATE CART QUANTITIES
========================== */
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        $qty = max(1, intval($qty)); // Ensure quantity is at least 1
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    header("Location: cart.php");
    exit;
}

/* ==========================
   REMOVE ITEM FROM CART
========================== */
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit;
}

/* ==========================
   CLEAR CART
========================== */
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Cloud Seven Furniture</title>
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        /* NAVIGATION */
        nav {
            background: #2196f3;
            padding: 15px;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #bbdefb;
        }

        /* PAGE TITLE */
        .page-title {
            text-align: center;
            font-size: 2rem;
            margin: 30px 0;
            color: #333;
            animation: fadeDown 1s ease;
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* CART TABLE */
        .cart-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeIn 0.8s ease-in-out;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #2196f3;
            color: white;
        }

        td img {
            width: 70px;
            border-radius: 8px;
        }

        /* INPUT FIELD */
        input[type="number"] {
            width: 60px;
            padding: 5px;
            text-align: center;
        }

        /* BUTTONS */
        .btn {
            display: inline-block;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .update-btn { background: #4caf50; }
        .update-btn:hover { background: #43a047; }

        .remove-btn { background: #f44336; }
        .remove-btn:hover { background: #d32f2f; }

        .checkout-btn { background: #2196f3; }
        .checkout-btn:hover { background: #1976d2; }

        .clear-btn { background: #ff9800; }
        .clear-btn:hover { background: #f57c00; }

        /* TOTAL SECTION */
        .total-section {
            text-align: right;
            font-size: 1.2rem;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* FOOTER */
        footer {
            background: #2196f3;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }

        /* EMPTY CART */
        .empty-cart {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
            padding: 40px 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <?php include "header.php"; ?>

    <h1 class="page-title">Your Shopping Cart</h1>

    <div class="cart-container">
        <?php if (!empty($_SESSION['cart'])): ?>
            <form method="POST" action="cart.php">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $grand_total = 0;
                        foreach ($_SESSION['cart'] as $item): 
                            $total = $item['price'] * $item['quantity'];
                            $grand_total += $total;
                        ?>
                        <tr>
                            <td><img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td>₱<?php echo number_format($total, 2); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn remove-btn">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total-section">
                    Grand Total: ₱<?php echo number_format($grand_total, 2); ?>
                </div>

                <div style="text-align: right;">
                    <button type="submit" name="update_cart" class="btn update-btn">Update Cart</button>
                    <a href="cart.php?clear=1" class="btn clear-btn">Clear Cart</a>
                    <a href="checkout.php" class="btn checkout-btn">Proceed to Checkout</a>
                </div>
            </form>
        <?php else: ?>
            <div class="empty-cart">
                Your cart is empty.<br>
                <a href="products.php" class="btn checkout-btn" style="margin-top:20px;">Shop Now</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Cloud Seven Furniture | All Rights Reserved
    </footer>

</body>
</html>
