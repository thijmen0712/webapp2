

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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT naam,email, rol FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $conn->prepare("
SELECT rb.id AS reis_boeking_id,r.id AS reis_id,r.titel,r.locatie,b.boekdatum,b.vertrekdatum,b.status
FROM reis_boekingen rb
JOIN reizen r ON rb.reis_id=r.id
JOIN boekingen b ON rb.boeking_id=b.id
WHERE b.user_id=?
");
$stmt->execute([$user_id]);
$reizen = $stmt->fetchAll();
?>
<!doctype html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Mijn Account</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="account-wrapper">
        <div class="account-col">
            <h2>Welkom, <?= htmlspecialchars($user['naam']) ?></h2>
            <p><strong>E-mail:</strong> <?= htmlspecialchars($user['email']) ?></p>

            <?php if (isset($user['rol']) && $user['rol'] === 'admin'): ?>
                <a href="admin.php" class="admin-button" style="display:inline-block; margin-bottom:10px; padding:8px 15px; background:#007bff; color:#fff; text-decoration:none; border-radius:4px;">Naar adminpagina</a>
            <?php endif; ?>

            <form method="post" action="logout.php">
                <button style="background-color: red;" type="submit">Uitloggen</button>
            </form>
            <h3>Accountgegevens wijzigen</h3>
            <form method="post" action="account_wijzigen.php">
                <label>Naam:
                    <input type="text" name="naam" value="<?= htmlspecialchars($user['naam']) ?>" required>
                </label>
                <label>E-mail:
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </label>
                <button type="submit">Opslaan</button>
            </form>
            <h3>Wachtwoord wijzigen</h3>
            <form method="post" action="wachtwoord_wijzigen.php">
                <label>Huidig wachtwoord:
                    <input type="password" name="huidig_wachtwoord" required>
                </label>
                <label>Nieuw wachtwoord:
                    <input type="password" name="nieuw_wachtwoord" required>
                </label>
                <label>Herhaal nieuw wachtwoord:
                    <input type="password" name="herhaal_wachtwoord" required>
                </label>
                <button type="submit">Wijzig wachtwoord</button>
            </form>
        </div>
        <div class="account-col reizen-lijst">
            <h3>Mijn Reizen</h3>
<?php if (count($reizen) > 0) { ?>
    <ul>
        <?php for ($i = 0; $i < count($reizen); $i++) {
            $reis = $reizen[$i];
            $vertrek = strtotime($reis['vertrekdatum']);
            $nu = time();
            $magAnnuleren = ($vertrek > $nu && $reis['status'] != 'geannuleerd');
        ?>
            <li>
                <strong><?php echo $reis['titel']; ?></strong> – <?php echo $reis['locatie']; ?><br>
                Boekdatum: <?php echo $reis['boekdatum']; ?><br>
                Vertrekdatum: <?php echo $reis['vertrekdatum']; ?><br>
                Status: <?php echo $reis['status']; ?><br>

                <?php if ($magAnnuleren) { ?>
                    <form method="post" action="annuleer.php">
                        <input type="hidden" name="reis_boeking_id" value="<?php echo $reis['reis_boeking_id']; ?>" />
                        <button type="submit">Annuleer</button>
                    </form>
                <?php } else { ?>
                    <em>Niet meer te annuleren</em>
                <?php } ?>

                <form method="post" action="recensie_toevoegen.php">
                    <input type="hidden" name="reis_id" value="<?php echo $reis['reis_id']; ?>" />
                    <label>Beoordeling:
                        <select name="beoordeling" required>
                            <option value="">Kies</option>
                            <option value="1">1 ster</option>
                            <option value="2">2 sterren</option>
                            <option value="3">3 sterren</option>
                            <option value="4">4 sterren</option>
                            <option value="5">5 sterren</option>
                        </select>
                    </label><br>
                    <label>Recensie:<br>
                        <textarea name="tekst" rows="3" cols="30" required></textarea>
                    </label><br>
                    <button type="submit">Plaats recensie</button>
                </form>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p>Je hebt nog geen reizen geboekt.</p>
<?php } ?>

        </div>
    </div>
</body>