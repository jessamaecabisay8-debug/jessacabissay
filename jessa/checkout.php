<?php
session_start();
require_once "db.php";

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Redirect to cart if it's empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total_price = 0;

// Calculate total price
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    if (!empty($full_name) && !empty($address) && !empty($phone)) {
        // Save to orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, address, phone, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssd", $user_id, $full_name, $address, $phone, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Save each item into order_items
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
            $stmt->execute();
            $stmt->close();
        }

        // Clear the cart after checkout
        unset($_SESSION['cart']);

        // Redirect to success page
        header("Location: order_success.php?order_id=" . $order_id);
        exit;
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Cloud Seven Furniture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

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
        }

        nav a:hover {
            color: #bbdefb;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .cart-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cart-summary th, .cart-summary td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .cart-summary th {
            background: #f1f1f1;
        }

        .form-section {
            margin-top: 30px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        .btn {
            display: inline-block;
            background: #2196f3;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 15px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #1976d2;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        footer {
            background: #2196f3;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <h2>Checkout</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Cart Summary -->
    <div class="cart-summary">
        <h3>Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₱<?php echo number_format($item['price'], 2); ?></td>
                        <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3 style="text-align: right;">Total: ₱<?php echo number_format($total_price, 2); ?></h3>
    </div>

    <!-- Delivery Information -->
    <div class="form-section">
        <h3>Delivery Information</h3>
        <form method="POST" action="">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" required></textarea>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required>

            <button type="submit" class="btn">Place Order</button>
        </form>
    </div>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Cloud Seven Furniture | All Rights Reserved
</footer>

</body>
</html>
