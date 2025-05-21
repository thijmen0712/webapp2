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
        <ul class="voordelen-lijst">
            <li>Al je boekingen op één plek</li>
            <li>Makkelijk en veilig toegang</li>
            <li>Nog sneller boeken</li>
        </ul>

        <form class="login-form" method="post" action="verwerk_account.php">
            <label for="email">E-mailadres</label>
            <input type="email" id="email" name="email" placeholder="E-mailadres" required>

            <label for="new-password">Nieuw wachtwoord</label>
            <input type="password" id="new-password" name="new-password" placeholder="Nieuw wachtwoord" required>

            <label for="confirm-password">Bevestig nieuw wachtwoord</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Bevestig nieuw wachtwoord" required>

            <div class="form-buttons">
                <button type="submit" class="ga-verder">Ga verder</button>
                <a href="account.php" class="annuleer">Annuleren</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>