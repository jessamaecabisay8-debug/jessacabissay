<?php
session_start();
require_once "db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch featured products
$products = [];
$sql = "SELECT id, name, description, price, image FROM products ORDER BY id DESC LIMIT 6";
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
    <title>Home - Cloud Seven Furniture</title>
    <style>
        /* ========== GLOBAL STYLES ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: #333;
        }

        a {
            text-decoration: none;
        }

        /* ========== NAVIGATION ========== */
        nav {
            background: rgba(33, 150, 243, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav a {
            color: white;
            margin: 0 20px;
            font-weight: 600;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #ffe082;
        }

        /* ========== HERO SECTION ========== */
        .hero {
            height: 70vh;
            background: url('images/hero.jpg') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .hero-content {
            position: relative;
            color: white;
            text-align: center;
            z-index: 2;
            animation: fadeIn 2s ease-in-out;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
            margin-bottom: 15px;
        }

        .hero-content p {
            font-size: 1.3rem;
            color: #f1f1f1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========== SECTION TITLE ========== */
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin: 50px 0 30px;
            color: #222;
            position: relative;
        }

        .section-title::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 4px;
            background: #2196f3;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* ========== PRODUCT GRID ========== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            animation: fadeSlide 0.8s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .product-card .info {
            padding: 15px;
        }

        .product-card .info h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: #333;
        }

        .product-card .info p {
            font-size: 0.9rem;
            color: #555;
            height: 40px;
            overflow: hidden;
        }

        .product-card .info .price {
            color: #2196f3;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 10px;
        }

        .add-to-cart-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 20px;
            background: #2196f3;
            color: white;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: background 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #1565c0;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========== FOOTER ========== */
        footer {
            background: #2196f3;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            position: relative;
        }

        footer p {
            margin: 5px 0;
        }

        footer a {
            color: #ffe082;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- NAVIGATION -->
    <?php include "header.php"; ?>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Cloud Seven Furniture</h1>
            <p>Transform your home with stylish and affordable furniture.</p>
        </div>
    </section>

    <!-- FEATURED PRODUCTS -->
    <h2 class="section-title">Featured Products</h2>
    <div class="products-grid">
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="product-card">
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="info">
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

    <!-- FOOTER -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Cloud Seven Furniture</p>
        <p>Crafted with ❤️ | <a href="about.php">About Us</a></p>
    </footer>
</body>
</html>
