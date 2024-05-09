<?php
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

// Get the user ID from the session
$userId = $_SESSION['userId'];

// Set default values for the month and year
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
$selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');

// Handle form submission to add a new expense
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $date = $_POST["date"];
    $money = $_POST["money"];

    $dateErr = $moneyErr = "";

    if (empty($date)) {
        $dateErr = "La date est requise";
    }

    if (empty($money)) {
        $moneyErr = "Le montant est requis";
    }

    if (empty($dateErr) && empty($moneyErr)) {
        // Insert the new expense into the database
        $database->addExpense($userId, $date, $money);
    }
}

// Retrieve the history data and total spending for the selected month/year
$historyData = $database->getExpenseHistoryForMonth($userId, $selectedYear, $selectedMonth);
$totalSpendingForPeriod = $database->getTotalSpendingForPeriod($userId, "$selectedYear-$selectedMonth-01", "$selectedYear-$selectedMonth-31");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Carburant</title>
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
        form {
            margin-top: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="date"],
        input[type="text"],
        input[type="submit"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #333;
        }
        th {
            background-color: #4caf50;
            color: white;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Carburant</h1>

        <!-- Form to select the month and year -->
        <form action="" method="post">
            <h2>Sélectionner le mois et l'année :</h2>
            <div>
                <label for="month">Mois :</label>
                <select id="month" name="month">
                    <?php
                    // Loop through the months and create options
                    for ($i = 1; $i <= 12; $i++) {
                        $month = str_pad($i, 2, "0", STR_PAD_LEFT); // Add leading zero if necessary
                        $selected = ($month == $selectedMonth) ? 'selected' : '';
                        echo "<option value=\"$month\" $selected>" . date("F", mktime(0, 0, 0, $i, 1)) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="year">Année :</label>
                <select id="year" name="year">
                    <?php
                    // Loop through the years and create options
                    for ($year = date("Y"); $year >= 2020; $year--) {
                        $selected = ($year == $selectedYear) ? 'selected' : '';
                        echo "<option value=\"$year\" $selected>$year</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="submit" value="Afficher">
        </form>

        <h2>Total Dépensé :</h2>
        <!-- Display total spending for the selected month/year -->
        <p>Total dépensé pour <?php echo date("F Y", mktime(0, 0, 0, $selectedMonth, 1)) . ' : '; ?><?php echo isset($totalSpendingForPeriod) ? $totalSpendingForPeriod . ' TND' : ''; ?></p>

        <!-- Form to add a new expense -->
        <form action="" method="post">
            <h2>Ajouter une Nouvelle Dépense</h2>
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" value="<?php echo isset($date) ? $date : ''; ?>">
            <br>
            <label for="money">Montant (en TND) :</label>
            <input type="text" id="money" name="money" value="<?php echo isset($money) ? $money : ''; ?>">
            <br>
            <span class="error"><?php echo isset($dateErr) ? $dateErr : ''; ?></span>
            <span class="error"><?php echo isset($moneyErr) ? $moneyErr : ''; ?></span>
            <br>
            <input type="submit" value="Ajouter">
        </form>

        <h2>Historique</h2>
        <!-- Table to display the history -->
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Montant (TND)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if $historyData is set and not empty -->
                <?php if(isset($historyData) && !empty($historyData)): ?>
                    <!-- Loop through the history data and populate the table rows -->
                    <?php foreach ($historyData as $entry): ?>
                        <tr>
                            <td><?php echo $entry['date']; ?></td>
                            <td><?php echo isset($entry['money']) ? $entry['money'] : 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Aucun historique disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>
