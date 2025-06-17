<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['reset_email'], $_SESSION['reset_code'])) {
    header('Location: wachtwoord_vergeten.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $nieuw = $_POST['nieuw_wachtwoord'] ?? '';
    $herhaal = $_POST['herhaal_wachtwoord'] ?? '';

    if ($code !== strval($_SESSION['reset_code'])) {
        $error = "Verificatiecode is onjuist.";
    } elseif ($nieuw !== $herhaal) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        $nieuwHash = password_hash($nieuw, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET wachtwoord = ? WHERE email = ?");
        $stmt->execute([$nieuwHash, $_SESSION['reset_email']]);

        unset($_SESSION['reset_code'], $_SESSION['reset_email']);

        header('Location: inloggen.php?message=wachtwoord_gewijzigd');
        exit();
    }
}
?>

<?php include 'header.php'; ?>

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
      <h2>Wachtwoord resetten</h2>

      <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="post" action="verwerk_reset.php">
        <label for="code">Verificatiecode</label>
        <input type="text" id="code" name="code" required>

        <label for="nieuw_wachtwoord">Nieuw wachtwoord</label>
        <input type="password" id="nieuw_wachtwoord" name="nieuw_wachtwoord" required>

        <label for="herhaal_wachtwoord">Herhaal nieuw wachtwoord</label>
        <input type="password" id="herhaal_wachtwoord" name="herhaal_wachtwoord" required>

        <button type="submit" class="ga-verder">Wijzig wachtwoord</button>
      </form>
    </div>
  </div>

<?php include 'footer.php'; ?>
</body>
</html>
