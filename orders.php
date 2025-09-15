<?php
session_start();
require_once "db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all orders for the logged-in user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Cloud Seven Furniture</title>
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
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #bbdefb;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeIn 0.8s ease;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .order-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fafafa;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .order-header strong {
            font-size: 1.1rem;
            color: #333;
        }

        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.Pending { background: #ffeb3b; color: #000; }
        .status.Processing { background: #64b5f6; color: #fff; }
        .status.Completed { background: #81c784; color: #fff; }
        .status.Cancelled { background: #e57373; color: #fff; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #f1f1f1;
        }

        .total {
            text-align: right;
            font-size: 1.1rem;
            font-weight: bold;
            margin-top: 10px;
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
    <h2>My Orders</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($order = $result->fetch_assoc()): ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <strong>Order ID: #<?php echo $order['id']; ?></strong><br>
                        Date: <?php echo date("F j, Y, g:i A", strtotime($order['created_at'])); ?>
                    </div>
                    <div class="status <?php echo $order['status']; ?>">
                        <?php echo htmlspecialchars($order['status']); ?>
                    </div>
                </div>

                <!-- Fetch order items -->
                <?php
                $order_id = $order['id'];
                $items_sql = "SELECT oi.quantity, oi.price, p.name, p.image 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = ?";
                $items_stmt = $conn->prepare($items_sql);
                $items_stmt->bind_param("i", $order_id);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();
                ?>

                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $items_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>
                                    <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                         alt="" style="width:60px; height:60px; border-radius:8px;">
                                </td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="total">
                    Total: ₱<?php echo number_format($order['total'], 2); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color:#555;">You have no orders yet.</p>
    <?php endif; ?>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Cloud Seven Furniture | All Rights Reserved
</footer>

</body>
</html>
