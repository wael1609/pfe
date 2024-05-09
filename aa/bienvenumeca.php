<?php
// Start the session
session_start();

// Check if the user is logged in as a mechanic
if (!isset($_SESSION['mechanicId'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Include the Database class
include 'database.php';

// Create a new instance of the Database class
$database = new Database();

// Retrieve all maintenance declarations
$maintenanceDeclarations = $database->getAllMaintenanceDeclarations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue Mécanicien</title>
    <!-- Add any CSS stylesheets here -->
</head>
<body>
    <h1>Bienvenue Mécanicien</h1>

    <!-- Display maintenance declarations -->
    <h2>Déclarations de maintenance</h2>
    <?php if (empty($maintenanceDeclarations)): ?>
        <p>Aucune déclaration de maintenance n'a été enregistrée pour le moment.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($maintenanceDeclarations as $declaration): ?>
                <li>
                    <strong>Matricule de la voiture:</strong> <?php echo $declaration['registration_number']; ?><br>
                    <strong>Partie de la voiture:</strong> <?php echo $declaration['car_part']; ?><br>
                    <strong>Description:</strong> <?php echo $declaration['description']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Add any other content or functionality specific to the mechanic's page -->

    <p><a href="logout.php">Déconnexion</a></p>
</body>
</html>
