<?php
session_start();
$melding = '';
include 'connect.php';

if (isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, naam, wachtwoord, rol FROM users WHERE email = :email AND actief = 1");
    $stmt->execute(['email' => $email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker) {
        if (password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            $_SESSION['user_id'] = $gebruiker['id'];
            $_SESSION['user_naam'] = $gebruiker['naam'];
            $_SESSION['user_rol'] = $gebruiker['rol'];
            header("Location: account.php");
            exit;
        } else {
            $melding = "Wachtwoord of E-mail onjuist.";
        }
    } else {
        $melding = "Gebruiker niet gevonden of inactief.";
    }
}
?>

<!doctype html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inloggen</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php include 'header.php'; ?>

<div class="login-center-wrapper">
  <div class="login-container">
    <h2>Log in of maak een account aan</h2>

    <?php if ($melding): ?>
      <p style="color:red;"><b><?= htmlspecialchars($melding) ?></b></p>
    <?php endif; ?>

    <form class="login-form" method="post" action="">
      <label for="email">E-mailadres</label>
      <input type="email" id="email" name="email" placeholder="E-mailadres" required />

      <div class="password-row">
        <label for="password">Wachtwoord</label>
        <a href="wachtwoord.php"><b>Wachtwoord vergeten?</b></a>
      </div>

      <input type="password" id="password" name="password" placeholder="Wachtwoord" required />

      <button class="loginsub" type="submit">Log in</button>

      <div class="signup-text">
        Heb je nog geen account? <a href="accountmaken.php"><b>Meld je nu aan</b></a>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
