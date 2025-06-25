<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    // Check of email bestaat
    include 'connect.php';
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Genereer een simpele code (6-cijferig)
        $code = rand(100000, 999999);
        $_SESSION['reset_code'] = $code;
        $_SESSION['reset_email'] = $email;

        // Hier normaal mail sturen met code, nu tonen we hem
        echo "Verificatiecode (gebruik deze): <strong>$code</strong>";
        echo '<br><a href="wachtwoord_reset.php">Ga naar wachtwoord reset</a>';
        exit;
    } else {
        echo "Dit e-mailadres is niet bekend.";
        exit;
    }
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
  <title>Wachtwoord vergeten</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="login-center-wrapper">
  <div class="login-container">
    <h2>Wachtwoord vergeten</h2>
    <form id="verificatie-form" method="post" action="verwerk_verificatie.php">
      <label for="email">E-mailadres</label>
      <input type="email" id="email" name="email" placeholder="E-mailadres" required />

      <button type="submit" name="stuur_code" class="verificatiecode">Stuur verificatiecode</button>
    </form>
    <a href="account.php" class="annuleer">Annuleren</a>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

