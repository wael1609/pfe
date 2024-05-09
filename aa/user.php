<?php
// Include the Database class
include 'database.php';

// Retrieve the user ID from the query parameter
$userId = isset($_GET['idu']) ? $_GET['idu'] : null;

// Check if user ID is provided
if ($userId === null) {
    echo "User ID is missing.";
    exit();
}

// Create a new instance of the Database class
$database = new Database();

// Call the getUserInformation() method
$userInfo = $database->getUserInformation($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        .container h1 {
            margin: 0 0 20px;
            font-size: 24px;
            text-align: center;
            color: #333;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .user-info label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .user-info p {
            margin: 0;
            color: #333;
        }

        .back-btn {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .fas {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Information</h1>
        <div class="user-info">
            <?php if(isset($userInfo) && !empty($userInfo)): ?>
                <label><i class="fas fa-id-badge"></i> User ID:</label>
                <p><?php echo $userInfo['idu']; ?></p>
                <label><i class="fas fa-user"></i> Login:</label>
                <p><?php echo $userInfo['login']; ?></p>
                <label><i class="fas fa-lock"></i> Password:</label>
                <p id="password"><?php echo str_repeat('*', strlen($userInfo['mdp'])); ?></p>
                <button class="show-password-btn" onclick="togglePassword()">Show</button>
                <script>
                    function togglePassword() {
                        var passwordField = document.getElementById('password');
                        var buttonText = document.querySelector('.show-password-btn');
                        if (passwordField.innerHTML.includes('*')) {
                            passwordField.innerHTML = '<?php echo $userInfo['mdp']; ?>';
                            buttonText.textContent = 'Hide';
                        } else {
                            passwordField.innerHTML = '<?php echo str_repeat('*', strlen($userInfo['mdp'])); ?>';
                            buttonText.textContent = 'Show';
                        }
                    }
                </script>
                <label><i class="fas fa-envelope"></i> Email:</label>
                <p><?php echo $userInfo['email']; ?></p>
            <?php else: ?>
                <p>User information not available.</p>
            <?php endif; ?>
        </div>
        <a href="bienvenu.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Bienvenue</a>
    </div>
</body>
</html>
