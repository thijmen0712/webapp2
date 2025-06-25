<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'] ?? '';
    $nieuw = $_POST['nieuw_wachtwoord'] ?? '';
    $herhaal = $_POST['herhaal_wachtwoord'] ?? '';

    if (!isset($_SESSION['reset_code'], $_SESSION['reset_email'])) {
        exit("Geen code, probeer opnieuw.");
    }
    if ($code != $_SESSION['reset_code']) {
        exit("Code klopt niet.");
    }
    if ($nieuw != $herhaal) {
        exit("Wachtwoorden zijn niet hetzelfde.");
    }

    $hash = password_hash($nieuw, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET wachtwoord = ? WHERE email = ?");
    $stmt->execute([$hash, $_SESSION['reset_email']]);

    unset($_SESSION['reset_code'], $_SESSION['reset_email']);

    exit("Wachtwoord is veranderd. <a href='inloggen.php'>Inloggen</a>");
}
?>


<?php
include 'header.php';
?>
<!doctype html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Wachtwoord resetten</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="login-center-wrapper">
  <div class="login-container">
    <h2>Nieuw wachtwoord instellen</h2>
    <form method="post" action="verwerk_reset.php">
      <label for="code">Verificatiecode</label>
      <input type="text" id="code" name="code" placeholder="Verificatiecode" required />

      <label for="nieuw_wachtwoord">Nieuw wachtwoord</label>
      <input type="password" id="nieuw_wachtwoord" name="nieuw_wachtwoord" placeholder="Nieuw wachtwoord" required />

      <label for="herhaal_wachtwoord">Herhaal nieuw wachtwoord</label>
      <input type="password" id="herhaal_wachtwoord" name="herhaal_wachtwoord" placeholder="Herhaal nieuw wachtwoord" required />

      <button type="submit">Wachtwoord wijzigen</button>
    </form>
    <a href="account.php" class="annuleer">Annuleren</a>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

