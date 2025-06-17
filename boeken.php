<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php';

// Als gebruiker niet is ingelogd, stuur door naar inloggen.php
if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit;
}

// Controleer of reis-ID is meegegeven in de URL
if (!isset($_GET['id'])) {
    echo "Geen reis geselecteerd.";
    exit;
}

$boodschap = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $vertrekdatum = $_POST['vertrekdatum'];
    $boekdatum = date('Y-m-d');
    $status = 'bevestigd';
    $reis_id = $_POST['reis_id'];
    $aantal_personen = $_POST['aantal_personen'];

    // Voeg toe aan `boekingen`
    $stmt1 = $conn->prepare("INSERT INTO boekingen (user_id, vertrekdatum, boekdatum, status)
                             VALUES (:user_id, :vertrekdatum, :boekdatum, :status)");
    $stmt1->execute([
        'user_id' => $user_id,
        'vertrekdatum' => $vertrekdatum,
        'boekdatum' => $boekdatum,
        'status' => $status
    ]);
    $boeking_id = $conn->lastInsertId();

    // Voeg toe aan `reis_boekingen` zonder extra_opties
    $stmt2 = $conn->prepare("INSERT INTO reis_boekingen (boeking_id, reis_id, aantal_personen)
                             VALUES (:boeking_id, :reis_id, :aantal_personen)");
    $stmt2->execute([
        'boeking_id' => $boeking_id,
        'reis_id' => $reis_id,
        'aantal_personen' => $aantal_personen
    ]);

    $boodschap = "✅ Boeking succesvol opgeslagen!";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Reis boeken</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="boeken-wrapper">
    <div class="container">
        <h1>Reis boeken</h1>
        
        <?php if ($boodschap): ?>
            <div class="success"><?= $boodschap ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="vertrekdatum">Vertrekdatum</label>
            <input type="date" name="vertrekdatum" required>

            <label for="aantal_personen">Aantal personen</label>
            <input type="number" name="aantal_personen" min="1" required>

            <input type="hidden" name="reis_id" value="<?= htmlspecialchars($_GET['id']) ?>">

            <button type="submit">Boek nu</button>
        </form>
    </div>
</div>
</body>
</html>
