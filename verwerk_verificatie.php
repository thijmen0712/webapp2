<?php
session_start();
include 'connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!$email) {
        $error = "Geen e-mailadres opgegeven.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Maak een 6-cijferige code
            $code = rand(100000, 999999);

            // Sla code en email op in sessie
            $_SESSION['reset_code'] = $code;
            $_SESSION['reset_email'] = $email;

            // Voor testen tonen we de code hier (in productie verstuur je deze per mail)
            $success = "Verificatiecode (gebruik deze): <strong>$code</strong>";
        } else {
            $error = "Dit e-mailadres is niet bekend.";
        }
    }
} else {
    $error = "Ongeldige aanvraag.";
}
?>

<?php include 'header.php'; ?>

<!doctype html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Verificatiecode verstuurd</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="login-center-wrapper">
    <div class="login-container">
      <h2>Wachtwoord vergeten</h2>

      <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
        <a href="wachtwoord_vergeten.php" class="annuleer">Probeer opnieuw</a>
      <?php elseif ($success): ?>
        <p class="success"><?= $success ?></p>
        <a href="wachtwoord_reset.php" class="ga-verder">Ga naar wachtwoord reset</a>
      <?php endif; ?>

    </div>
  </div>

<?php include 'footer.php'; ?>
</body>
</html>
