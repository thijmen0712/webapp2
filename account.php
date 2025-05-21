<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn account</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include 'header.php';
?>
<div class="login-center-wrapper">
    <div class="login-container">
        <h2>Log in of maak een account aan</h2>

        <form class="login-form" method="post" action="verwerk_login.php">
            <label for="email">E-mailadres</label>
            <input type="email" id="email" name="email" placeholder="E-mailadres" required />

            <div class="password-row">
                <label for="password">Wachtwoord</label>
                <a href="#">Wachtwoord vergeten?</a>
            </div>
            <input type="password" id="password" name="password" placeholder="Wachtwoord" required />

            <button type="submit">Log in</button>

            <div class="signup-text">
                Heb je nog geen account? <a href="accountmaken.php">Meld je nu aan</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>