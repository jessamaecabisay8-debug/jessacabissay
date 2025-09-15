<?php
$host = "127.0.0.1";
$user = "root"; // Default user for XAMPP
$pass = "";     // Default password is empty
$db   = "namie"; // Your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
