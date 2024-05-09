<?php
// Start the session
session_start();

// Function to get the difference in days between two dates
function getDaysDifference($date1, $date2) {
    $diff = strtotime($date1) - strtotime($date2);
    return floor($diff / (60 * 60 * 24));
}

// Sample lavage history (you would retrieve this from a database)
$lavageHistory = isset($_SESSION['lavageHistory']) ? $_SESSION['lavageHistory'] : [];

// Check if the user has requested a lavage before
$lastLavageDate = isset($lavageHistory[count($lavageHistory) - 1]) ? $lavageHistory[count($lavageHistory) - 1] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_lavage'])) {
    if ($lastLavageDate === null) {
        // First time requesting a lavage
        $permission = true;
        $message = "Vous avez la permission de faire un lavage.";
        // Save the request
        $lavageHistory[] = date("Y-m-d");
        $_SESSION['lavageHistory'] = $lavageHistory;
    } else {
        // Calculate days since last lavage
        $daysSinceLastLavage = getDaysDifference(date("Y-m-d"), $lastLavageDate);
        if ($daysSinceLastLavage > 15) {
            // More than 15 days since last lavage
            $permission = true;
            $message = "Vous avez la permission de faire un lavage.";
            // Save the request
            $lavageHistory[] = date("Y-m-d");
            $_SESSION['lavageHistory'] = $lavageHistory;
        } else {
            // Less than 15 days since last lavage
            $permission = false;
            $daysRemaining = 15 - $daysSinceLastLavage;
            $message = "Désolé, vous devez attendre encore $daysRemaining jours avant de demander un nouveau lavage.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f4f6;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
            margin-bottom: 20px;
        }
        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .history-button {
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .history-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Demander un lavage</h1>
        <?php if(isset($message)): ?>
        <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <button type="submit" name="request_lavage">Demander un lavage</button>
        </form>
        <a href="historique_lavages.php"><button class="history-button">Historique des lavages</button></a>
    </div>
</body>
</html>
