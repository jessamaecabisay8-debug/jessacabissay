<?php
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($_POST['password'])) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            header("Location: login.php?success=1");
            exit;
        } else {
            $error = "Username or email already exists!";
        }
        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Furniture Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        .register-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            width: 320px;
            animation: slideUp 0.7s ease-in-out;
        }
        @keyframes slideUp {
            from {transform: translateY(50px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }
        .register-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .register-box input[type="text"],
        .register-box input[type="email"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .register-box button {
            width: 100%;
            padding: 10px;
            background: #2196f3;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .register-box button:hover {
            background: #1976d2;
        }
        .error {
            color: red;
            text-align: center;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Create Account</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
