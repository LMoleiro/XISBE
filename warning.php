<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warning Message</title>
    <style>
        .warning-message {
            background-color: #ffcc00;
            color: #333;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid #ff9114;
            border-radius: 8px;
        }

        .welcome-message {
            font-size: 1.2rem;
            color: #333;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['email'])) {
        // If the user is logged in, show the welcome message
        echo "<div class='welcome-message'>Welcome, " . $_SESSION['email'] . "! After signing up for activities, please wait. It should take about a minute to confirm your enrollment. </div>";
    } else {
        // If the user is not logged in, show the warning message
        echo "<div class='warning-message'>In order to see the event's activities, please login or signup.</div>";
    }
    ?>
</body>
</html>
