<?php
session_start();
require_once "db.php";

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Cloud Seven Furniture</title>
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
            color: #333;
            line-height: 1.6;
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

        /* HEADER SECTION */
        .about-header {
            background: url('images/hero.jpg') center/cover no-repeat;
            height: 40vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            color: white;
        }

        .about-header::after {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
        }

        .about-header h1 {
            position: relative;
            font-size: 3rem;
            z-index: 2;
            animation: fadeDown 1.5s ease-in-out;
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ABOUT CONTENT */
        .about-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            text-align: center;
        }

        .about-container h2 {
            font-size: 2rem;
            color: #2196f3;
            margin-bottom: 15px;
            animation: fadeIn 1.5s ease;
        }

        .about-container p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 20px;
            animation: fadeIn 2s ease;
        }

        /* TEAM SECTION */
        .team-section {
            margin-top: 50px;
        }

        .team-title {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .team-member {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: slideUp 0.8s ease;
        }

        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .team-member h4 {
            margin-bottom: 5px;
            font-size: 1.2rem;
            color: #2196f3;
        }

        .team-member p {
            font-size: 0.9rem;
            color: #666;
        }

        /* FOOTER */
        footer {
            background: #2196f3;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }

        /* ANIMATIONS */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Include Navigation -->
    <?php include "header.php"; ?>

    <!-- About Header Section -->
    <section class="about-header">
        <h1>About Us</h1>
    </section>

    <!-- About Content -->
    <div class="about-container">
        <h2>Welcome to Cloud Seven Furniture</h2>
        <p>
            At Cloud Seven Furniture, we believe that every home deserves to be filled with 
            stylish and comfortable furniture. Established in Malungon, Sarangani Province, 
            our mission is to provide high-quality furniture pieces at affordable prices.
        </p>
        <p>
            From modern designs to classic styles, our products are carefully crafted to 
            bring elegance and comfort to your living space. We take pride in our exceptional 
            customer service and a seamless shopping experience, both online and in-store.
        </p>

        <!-- Team Section -->
        <div class="team-section">
            <h3 class="team-title">Meet Our Team</h3>
            <div class="team-grid">
                <div class="team-member">
                    <img src="images/team1.jpg" alt="Team Member">
                    <h4>Jessa Mae Cabisay</h4>
                    <p>Founder & CEO</p>
                </div>
                <div class="team-member">
                    <img src="images/team2.jpg" alt="Team Member">
                    <h4>John Doe</h4>
                    <p>Product Designer</p>
                </div>
                <div class="team-member">
                    <img src="images/team3.jpg" alt="Team Member">
                    <h4>Jane Smith</h4>
                    <p>Marketing Lead</p>
                </div>
                <div class="team-member">
                    <img src="images/team4.jpg" alt="Team Member">
                    <h4>Mark Wilson</h4>
                    <p>Customer Support</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date("Y"); ?> Cloud Seven Furniture | All Rights Reserved
    </footer>

</body>
</html>
