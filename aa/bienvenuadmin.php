<?php
// Start the session
session_start();

// Include your database connection file
require_once 'database.php';

// Create an instance of the Database class
$db = new Database();

// Initialize variables
$message = '';

// Check if the form is submitted to add a new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    
    // Check if the email and password are not empty
    if (!empty($email) && !empty($password)) {
        // Add the new user to the database
        $result = $db->addUser($email, $password, $type);
        if ($result) {
            $message = 'Utilisateur ajouté avec succès.';
        } else {
            $message = 'Erreur lors de l\'ajout de l\'utilisateur.';
        }
    } else {
        $message = 'Veuillez saisir une adresse e-mail et un mot de passe.';
    }
}

// Sample lavage history (you would retrieve this from a database)
$lavageHistory = isset($_SESSION['lavageHistory']) ? $_SESSION['lavageHistory'] : [];

// Get user information if username is provided
$userInfo = null;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    $username = $_GET['username'];
    $userInfo = $db->getUserInfoByUsername($username);
    if ($userInfo) {
        $userId = $userInfo['idu'];
        $lavageHistory = $db->getLavageHistoryByUserId($userId);
        $carburantHistory = $db->getCarburantHistoryByUserId($userId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        main {
            padding: 20px;
        }
        section {
            margin-bottom: 30px;
        }
        .user-information,
        .carburant-history,
        .lavage-history {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .user-information h2,
        .carburant-history h2,
        .lavage-history h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }
        .user-information ul {
            list-style-type: none;
            padding: 0;
        }
        .user-information ul li {
            margin-bottom: 10px;
        }
        .user-information ul li:last-child {
            margin-bottom: 0;
        }
        .carburant-history table,
        .lavage-history table {
            width: 100%;
            border-collapse: collapse;
        }
        .carburant-history th,
        .carburant-history td,
        .lavage-history th,
        .lavage-history td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .carburant-history th,
        .lavage-history th {
            background-color: #f2f2f2;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .button:hover {
            background-color: #555;
        }
        /* Styles for the "Ajouter un nouvel utilisateur" section */
        .add-user-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .add-user-form h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .submit-button {
            display: block;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .message {
            margin-top: 10px;
            color: #4caf50;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <nav>
        <!-- Add navigation links here if needed -->
    </nav>
    <main>
        <section class="search-form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <input type="text" name="username" placeholder="Search by username">
                <button type="submit">Search</button>
            </form>
        </section>
        <?php if ($userInfo): ?>
            <section class="user-information">
                <h2>User Information</h2>
                <ul>
                    <li><strong>ID:</strong> <?php echo $userInfo['idu']; ?></li>
                    <li><strong>Email:</strong> <?php echo $userInfo['email']; ?></li>
                    <li><strong>Type:</strong> <?php echo $userInfo['type']; ?></li>
                </ul>
            </section>
            <section class="carburant-history">
                <h2>Carburant History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carburantHistory as $entry): ?>
                            <tr>
                                <td><?php echo $entry['date']; ?></td>
                                <td><?php echo $entry['money']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <section class="lavage-history">
                <h2>Lavage History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <!-- Add other table headers here if needed -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lavageHistory as $entry): ?>
                            <tr>
                                <td><?php echo $entry['date']; ?></td>
                                <!-- Add other table data here if needed -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>
        
        <!-- Add the form to add a new user -->
        <section class="add-user-form">
            <h2>Ajouter un nouvel utilisateur</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select id="type" name="type" required>
                        <option value="admin">Admin</option>
                        <option value="user">Utilisateur</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="submit-button">Ajouter</button>
            </form>
            <?php if (!empty($message)): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
        </section>
        
        <!-- Add the button to redirect to adduser.php -->
        <section class="add-user-button">
            <a href="adduser.php" class="button">Add / Modify / Delete Users</a>
        </section>
        
        <!-- Always show the "continuer en tant qu'un utilisateur" button -->
        <a href="bienvenu.php" class="button">Continuer en tant qu'un utilisateur</a>
    </main>
</body>
</html>
