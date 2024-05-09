<?php
// Start the session
session_start();

// Include the database connection
require_once 'database.php';

// Create a new Database instance
$db = new Database();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit;
}

// Get the user's messages
$userId = $_SESSION['userId'];
$messages = $db->getMessages($userId);

// Check for errors
if ($messages === false) {
    // Handle error, such as displaying an error message or logging the error
    echo "Error: Failed to retrieve messages.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .message {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Messages</h1>
    <?php if (empty($messages)): ?>
        <p>No messages available.</p>
    <?php else: ?>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <strong>From Admin:</strong> <?php echo $message['message_content']; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
