<?php
session_start();
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Successful</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f8f9fa; }
        .success-box {
            max-width: 500px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease;
        }
        h2 { color: #28a745; }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #2196f3;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn:hover { background: #1976d2; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="success-box">
        <h2>Thank You for Your Order!</h2>
        <p>Your order ID is <strong>#<?php echo $order_id; ?></strong>.</p>
        <p>We'll process your order and update you shortly.</p>
        <a href="home.php" class="btn">Back to Home</a>
    </div>
</body>
</html>
