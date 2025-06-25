<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: inloggen.php");
    exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Geen reis geselecteerd.";
    exit;
}
$reis_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM reizen WHERE id=:id");
$stmt->execute(['id' => $reis_id]);
$reis = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$reis) {
    echo "Reis niet gevonden.";
    exit;
}
$boodschap = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $vertrekdatum = $_POST['vertrekdatum'];
    $retourdatum = $_POST['retourdatum'];
    $boekdatum = date('Y-m-d');
    $status = 'bevestigd';
    $aantal_personen = $_POST['aantal_personen'];
    $vervoer = $_POST['vervoer'];
    $extra_bagage = isset($_POST['extra_bagage']) ? 1 : 0;
    $reisverzekering = isset($_POST['reisverzekering']) ? 1 : 0;
    $annulering = isset($_POST['annulering']) ? 1 : 0;
    $nachten = (strtotime($retourdatum) - strtotime($vertrekdatum)) / 86400;
    if ($nachten < 1) {
        $nachten = 1;
    }
    $prijs_per_nacht = $reis['prijs'];
    $basis = $prijs_per_nacht * $nachten * $aantal_personen;
    $extra = 0;
    if ($extra_bagage)
        $extra += 20 * $aantal_personen;
    if ($reisverzekering)
        $extra += 15 * $aantal_personen;
    if ($annulering)
        $extra += 10 * $aantal_personen;
    $totaalprijs = $basis + $extra;
    $stmt1 = $conn->prepare("INSERT INTO boekingen (user_id, vertrekdatum, retourdatum, boekdatum, status, vervoer, extra_bagage, reisverzekering, annulering, totaalprijs, nachten) VALUES (:user_id,:vertrekdatum,:retourdatum,:boekdatum,:status,:vervoer,:extra_bagage,:reisverzekering,:annulering,:totaalprijs,:nachten)");
    $stmt1->execute([
        'user_id' => $user_id,
        'vertrekdatum' => $vertrekdatum,
        'retourdatum' => $retourdatum,
        'boekdatum' => $boekdatum,
        'status' => $status,
        'vervoer' => $vervoer,
        'extra_bagage' => $extra_bagage,
        'reisverzekering' => $reisverzekering,
        'annulering' => $annulering,
        'totaalprijs' => $totaalprijs,
        'nachten' => $nachten
    ]);
    $boeking_id = $conn->lastInsertId();
    $stmt2 = $conn->prepare("INSERT INTO reis_boekingen (boeking_id, reis_id, aantal_personen) VALUES (:boeking_id,:reis_id,:aantal_personen)");
    $stmt2->execute(['boeking_id' => $boeking_id, 'reis_id' => $reis_id, 'aantal_personen' => $aantal_personen]);
    $boodschap = "✅ Boeking opgeslagen! Totale prijs: €" . number_format($totaalprijs, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Reis boeken - <?php echo htmlspecialchars($reis['titel']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <a href="javascript:history.back()" class="terug-knop">Terug</a>
    <div class="pagina-container">
        <div class="header">
            <h1><?php echo htmlspecialchars($reis['titel']); ?></h1>
            <p><?php echo htmlspecialchars($reis['locatie']); ?> – <a href="<?php echo $reis['locatie_link']; ?>"
                    target="_blank" class="toon-op-kaart">Toon op kaart</a></p>
        </div>
        <div class="foto-grid">
            <?php if (!empty($reis['afbeelding'])): ?><img
                    src="images/<?php echo $reis['afbeelding']; ?>"><?php endif; ?>
        </div>
        <div class="info-blokken">
            <div class="beschrijving">
                <h2>Beschrijving</h2>
                <p><?php echo nl2br(htmlspecialchars($reis['beschrijving_lang'])); ?></p>
            </div>
            <div class="boekingsformulier">
                <h2>Boek deze reis</h2>
                <?php if ($boodschap): ?>
                    <div class="success"><?php echo $boodschap; ?></div><?php endif; ?>
                <form method="post" id="boekForm">
                    <label>Vertrekdatum</label>
                    <input type="date" name="vertrekdatum" id="vertrek" required>
                    <label>Terugdatum</label>
                    <input type="date" name="retourdatum" id="retour" required>
                    <label>Aantal personen</label>
                    <input type="number" name="aantal_personen" id="personen" min="1" value="1" required>
                    <label>Vervoer</label>
                    <select name="vervoer" id="vervoer" required>
                        <option value="vliegtuig">✈️ Vliegtuig</option>
                        <option value="bus">🚌 Bus</option>
                        <option value="eigen">🚗 Eigen vervoer</option>
                    </select>
                    <fieldset>
                        <legend>Extra opties</legend>
                        <label><input type="checkbox" name="extra_bagage" id="bagage" value="1"> 🧳 Extra bagage (€20
                            p.p.)</label><br>
                        <label><input type="checkbox" name="reisverzekering" id="verzekering" value="1"> 🛡️
                            Reisverzekering (€15 p.p.)</label><br>
                        <label><input type="checkbox" name="annulering" id="annulering" value="1"> ❌
                            Annuleringsverzekering (€10 p.p.)</label>
                    </fieldset>
                    <input type="hidden" name="reis_id" value="<?php echo $reis['id']; ?>">
                    <button type="submit">Bevestig boeking</button>
                </form>
                <p id="prijsWeergave"></p>
            </div>
        </div>
        <div class="prijs-boek">
            <p><strong>Vanaf €<?php echo $reis['prijs']; ?></strong> per nacht p.p.</p>
        </div>
    </div>
    <script>
        const prijsPerNacht = <?php echo $reis['prijs']; ?>;
        function bereken() {
            let vertrek = document.getElementById('vertrek').value;
            let retour = document.getElementById('retour').value;
            let personen = parseInt(document.getElementById('personen').value) || 1;
            if (!vertrek || !retour) return;
            let nacht = (new Date(retour) - new Date(vertrek)) / 86400000;
            if (nacht < 1) nacht = 1;
            let basis = prijsPerNacht * nacht * personen;
            let extra = 0;
            if (document.getElementById('bagage').checked) extra += 20 * personen;
            if (document.getElementById('verzekering').checked) extra += 15 * personen;
            if (document.getElementById('annulering').checked) extra += 10 * personen;
            let totaal = basis + extra;
            document.getElementById('prijsWeergave').innerText = 'Totale prijs: €' + totaal.toFixed(2).replace('.', ',');
        }
        document.getElementById('boekForm').addEventListener('input', bereken);
    </script>
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