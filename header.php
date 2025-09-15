
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <a href="home.php">Home</a>
    <a href="products.php">Products</a>
    <a href="about.php">About</a>
    <a href="cart.php">Cart</a>
    <a href="checkout.php">Checkout</a>
    <a href="orders.php">Orders</a>
    <a href="contact.php">Contact</a>
    <a href="profile.php">Profile</a>
    <a href="feedback.php">Feedback</a>
    <a href="logout.php">Logout</a>
</nav>
