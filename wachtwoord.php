<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord vergeten</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include 'header.php';
?>
<div class="login-center-wrapper">
    <div class="login-container">
        <h2>Log in of maak een account aan</h2>
        <form class="login-form">
            <label for="email">E-mailadres</label>
            <input type="email" id="email" name="email" placeholder="E-mailadres" required />

            <button type="button" class="verificatiecode">Stuur verificatiecode</button>

            <div class="form-buttons">
                <button type="submit" class="ga-verder">Ga verder</button>
                <a href="account.php" class="annuleer">Annuleren</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>