<?php
// Start the session
session_start();

// Include your database connection file
require_once 'database.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['adminId'])) {
    // Redirect to login page or display an error message
    header("Location: login.php");
    exit;
}

// Initialize variables
$userInfo = null;
$lavageHistory = null;
$carburantHistory = null;

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    // Get the user name from the form
    $username = $_POST['username'];

    // Retrieve user information
    $userInfo = $db->getUserInfoByName($username);

    if ($userInfo) {
        // Retrieve lavage history
        $lavageHistory = $db->getLavageHistoryByUserId($userInfo['id']);

        // Retrieve carburant history
        $carburantHistory = $db->getCarburantHistoryByUserId($userInfo['id']);
    } else {
        // User not found
        $errorMessage = "Utilisateur non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de l'utilisateur</title>
    <!-- Include your CSS stylesheets here -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        section {
            margin-bottom: 30px;
        }
        .user-info {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .user-info h2 {
            margin-top: 0;
        }
        .user-info p {
            margin-bottom: 10px;
        }
        .history {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .history h2 {
            margin-top: 0;
        }
        .history ul {
            list-style-type: none;
            padding: 0;
        }
        .history ul li {
            margin-bottom: 10px;
        }
        .history ul li:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Historique de l'utilisateur</h1>
    </header>
    <main>
        <section>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="username">Rechercher par nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
                <button type="submit" name="search">Rechercher</button>
            </form>
            <?php if(isset($errorMessage)): ?>
            <p><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </section>
        <?php if($userInfo): ?>
        <section class="user-info">
            <h2>Informations de l'utilisateur</h2>
            <p><strong>Nom d'utilisateur :</strong> <?php echo $userInfo['username']; ?></p>
            <p><strong>Email :</strong> <?php echo $userInfo['email']; ?></p>
            <!-- Add more user information fields as needed -->
        </section>
        <section class="history">
            <h2>Historique de lavage</h2>
            <?php if($lavageHistory): ?>
            <ul>
                <?php foreach($lavageHistory as $lavage): ?>
                <li>Date: <?php echo $lavage['date']; ?>, Type: <?php echo $lavage['type']; ?></li>
                <!-- Add more lavage history fields as needed -->
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p>Aucun historique de lavage disponible.</p>
            <?php endif; ?>
        </section>
        <section class="history">
            <h2>Historique de carburant</h2>
            <?php if($carburantHistory): ?>
            <ul>
                <?php foreach($carburantHistory as $carburant): ?>
                <li>Date: <?php echo $carburant['date']; ?>, Type: <?php echo $carburant['type']; ?></li>
                <!-- Add more carburant history fields as needed -->
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p>Aucun historique de carburant disponible.</p>
            <?php endif; ?>
        </section>
        <?php endif; ?>
    </main>
</body>
</html>
