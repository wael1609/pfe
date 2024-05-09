<?php
// Start the session
session_start();

// Sample lavage history (you would retrieve this from a database)
$lavageHistory = isset($_SESSION['lavageHistory']) ? $_SESSION['lavageHistory'] : [];

// Check if the user has any lavage history
if (empty($lavageHistory)) {
    $message = "Aucun historique de lavages disponible.";
} else {
    $message = "Historique des lavages:";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Lavages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
        }
        .history-item {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .history-item span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Historique des Lavages</h1>
        <p><?php echo $message; ?></p>
        <?php foreach ($lavageHistory as $date): ?>
            <div class="history-item">Lavage effectué le <span><?php echo $date; ?></span></div>
        <?php endforeach; ?>
    </div>
</body>
</html>
