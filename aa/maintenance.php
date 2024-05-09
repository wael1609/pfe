<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}

// Include the Database class
include 'database.php';

// Create a new instance of the Database class
$database = new Database();

// Initialize variables
$message = '';

// Fetch immatriculations from the database
$immatriculations = $database->getImmatriculations();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a part of the car is selected
    if (isset($_POST['car_part']) && isset($_POST['description']) && isset($_POST['registration_number'])) {
        // Get the selected car part, description, and registration number
        $carPart = $_POST['car_part'];
        $description = $_POST['description'];
        $registrationNumber = $_POST['registration_number'];
        
        // Get the user ID from the session
        $userId = $_SESSION['userId'];
        
        // Save the user's declaration in the database
        $result = $database->saveCarPartDeclaration($userId, $carPart, $description, $registrationNumber);
        
        // Check if the declaration is saved successfully
        if ($result) {
            $message = 'Votre déclaration a été enregistrée avec succès.';
        } else {
            $message = 'Une erreur s\'est produite lors de l\'enregistrement de votre déclaration.';
        }
    } else {
        $message = 'Veuillez sélectionner une partie de la voiture, fournir une description et saisir le numéro d\'immatriculation.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        p {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        select, textarea, input[type="text"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Maintenance de la voiture</h1>

        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="registration_number">Numéro d'immatriculation de la voiture :</label>
                <select name="registration_number" id="registration_number" required>
                    <option value="" selected disabled>Sélectionnez une immatriculation</option>
                    <?php foreach ($immatriculations as $immatriculation): ?>
                        <option value="<?php echo $immatriculation; ?>"><?php echo $immatriculation; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="car_part">Sélectionnez une partie de la voiture :</label>
                <select name="car_part" id="car_part" required>
                    <option value="" selected disabled>Sélectionnez une option</option>
                    <option value="La pompe à eau">La pompe à eau</option>
                    <option value="Les injecteurs">Les injecteurs</option>
                    <option value="La culasse">La culasse</option>
                    <option value="Le vilebrequin">Le vilebrequin</option>
                    <option value="La courroie de distribution">La courroie de distribution</option>
                    <option value="La pompe de direction assistée">La pompe de direction assistée</option>
                    <option value="Les pistons">Les pistons</option>
                    <option value="Les bougies de préchauffage">Les bougies de préchauffage</option>
                    <option value="Les filtres à huile et à air">Les filtres à huile et à air</option>
                    <option value="Les plaquettes de frein, les disques de frein et les freins">Les plaquettes de frein, les disques de frein et les freins</option>
                    <option value="Les pneus">Les pneus</option>
                    <option value="La batterie">La batterie</option>
                    <option value="L’embrayage">L’embrayage</option>
                    <option value="Les amortisseurs">Les amortisseurs</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description du problème :</label>
                <textarea name="description" id="description" rows="4" required></textarea>
            </div>
            <button type="submit" class="submit-button">Envoyer</button>
        </form>
        
        <p><a href="index.php">Retour à la page d'accueil</a></p>
    </div>
</body>
</html>
