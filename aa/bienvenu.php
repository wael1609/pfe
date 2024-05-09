<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* Change background image to parc.jpg */
            background-image: url('parc.jpg');
            /* Adjust background properties */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            position: relative;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
        .box {
            width: 300px;
            height: 300px;
            border: 2px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: flex-end; /* Align text at the bottom */
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 20px;
            text-decoration: none;
            color: #333;
            background-size: cover;
        }
        .box:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
        }
        .box h2 {
            margin: 0;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 100px;
            height: auto;
        }
        .company-text {
            position: absolute;
            top: calc(20px + 100px + 10px);
            left: 20px;
            font-size: 14px;
            color: #000;
        }
        /* Update background images for different boxes */
        .box[href="user.php"],
        .box[href="user.php?idu=<?php echo isset($_SESSION['userId']) ? $_SESSION['userId'] : ''; ?>"] {
            background-image: url('user.png'); /* Set background image for the "Utilisateur" box */
            background-color: rgba(255, 255, 255, 0.8); /* Light background color for readability */
        }
        .box[href="carburant.php"] {
            background-image: url('carburant.png');
        }
        .box[href="lavage.php"] {
            background-image: url('lavage.png');
            color: white;
        }
        .box[href="maintenance.php"] {
            background-image: url('maint.png');
        }
        .box[href="avis.php"] {
            background-image: url('avis.png'); /* Set background image for the "Avis" box */
        }
        .messages-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .messages-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <img src="logo.png" alt="Logo" class="logo">
    <div class="company-text"></div>
    <h1>Bienvenue dans votre parc</h1>
    <div class="container">
        <a href="user.php?idu=<?php echo isset($_SESSION['userId']) ? $_SESSION['userId'] : ''; ?>" class="box">
            <h2>Utilisateur</h2>
        </a>
        <a href="carburant.php" class="box">
            <h2>Carburant</h2>
        </a>
        <a href="lavage.php" class="box">
            <h2>Lavage</h2>
        </a>
        <a href="maintenance.php" class="box">
            <h2>Maintenance</h2>
        </a>
        <a href="avis.php" class="box"> <!-- Changed href to avis.php and text to "Avis" -->
            <h2>Avis</h2>
        </a>
    </div>
    <!-- Add a button for accessing messages -->
    <a href="messages.php" class="messages-button">Voir les messages</a>
</body>
</html>
