<?php
session_start();
require_once "db.php";

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all products
$products = [];
$sql = "SELECT id, name, description, price, image FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Cloud Seven Furniture</title>
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

        /* TITLE */
        .section-title {
            text-align: center;
            font-size: 2rem;
            margin: 30px 0;
            color: #333;
        }

        /* PRODUCT GRID */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* PRODUCT CARD */
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.8s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
            text-align: center;
        }

        .product-info h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .product-info p {
            font-size: 0.9rem;
            color: #666;
            height: 40px;
            overflow: hidden;
        }

        .price {
            color: #2196f3;
            font-weight: bold;
            margin-top: 10px;
        }

        /* BUTTON */
        .add-to-cart-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 8px 15px;
            background: #2196f3;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #1976d2;
        }

        /* FOOTER */
        footer {
            background: #2196f3;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }

        /* ANIMATION */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Include Navigation -->
    <?php include "header.php"; ?>

    <h2 class="section-title">Our Products</h2>

    <div class="products-grid">
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="product-card">
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="price">₱<?php echo number_format($product['price'], 2); ?></div>
                        <a href="cart.php?add=<?php echo $product['id']; ?>" class="add-to-cart-btn">Add to Cart</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p style="text-align:center; width:100%;">No products available at the moment.</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Cloud Seven Furniture | All Rights Reserved
    </footer>

</body>
</html>
