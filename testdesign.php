
<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include 'connect.php';
include 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'Ongeldige reis.'; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM reizen WHERE id = :id");
$stmt->execute(['id'=>$id]);
$reis = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$reis) { echo 'Reis niet gevonden.'; exit; }

/* recensies */
$recStmt = $conn->prepare("
    SELECT r.beoordeling,r.tekst,r.datum,u.naam
    FROM recensies r
    JOIN users u ON u.id=r.user_id
    WHERE r.reis_id=:id AND r.goedgekeurd=1
    ORDER BY r.datum DESC
");
$recStmt->execute(['id'=>$id]);
$recensies = $recStmt->fetchAll(PDO::FETCH_ASSOC);
$aantal = count($recensies);
$gem = $aantal ? round(array_sum(array_column($recensies,'beoordeling'))/$aantal,1) : null;
$eerste = $recensies[0] ?? null;
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= htmlspecialchars($reis['titel']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="verblijf-container">
    <div class="verblijf-links">
        <h2 class="verblijf-titel"><?= htmlspecialchars($reis['titel']) ?></h2>
        <p class="verblijf-adres">
            <?= htmlspecialchars($reis['locatie']) ?> –
            <a class="blauw" href="<?= htmlspecialchars($reis['locatie_link']) ?>" target="_blank">
                Uitstekende locatie - toon op kaart
            </a>
        </p>

        <div class="hoofdfoto">
            <img style="width:100%;height:100%;object-fit:cover;border-radius:12px"
                 src="images/<?= htmlspecialchars($reis['afbeelding']) ?>" alt="">
        </div>

        <div class="fotoblok">
            <?php
            $imgArr = ['afbeelding2','afbeelding3','afbeelding4','afbeelding5'];
            foreach ($imgArr as $idx=>$col):
                if (!$reis[$col]) continue; ?>
                <div class="foto">
                    <img style="width:100%;height:100%;object-fit:cover;border-radius:10px"
                         src="images/<?= htmlspecialchars($reis[$col]) ?>" alt="">
                </div>
            <?php endforeach; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include 'header.php';
?>

<div class="verblijf-container">
    <div class="verblijf-links">
        <h2 class="verblijf-titel">Tour Eiffel terrasse vue seine</h2>
        <p class="verblijf-adres">
            7 Avenue de Lamballe, 16e Arrondissement, 75016 Parijs, Frankrijk –
            <span class="blauw">Uitstekende locatie - toon op kaart</span>
        </p>
        <div class="hoofdfoto"></div>
        <div class="fotoblok">
            <div class="foto"></div>
            <div class="foto"></div>
            <div class="foto"></div>
            <div class="foto"></div>
            <div class="foto"></div>
            <div class="foto">+3 foto's</div>

        </div>
    </div>

    <div class="verblijf-rechts">

        <a class="reserveerknop" href="boeken.php?reis=<?= $reis['id'] ?>">
            Reserveer je verblijf in dit appartement<br>€<?= number_format($reis['prijs'],0,',','.') ?> p.p.
        </a>

        <div class="recensieblok">
            <?php if($aantal): ?>
                <p><strong><?= $gem >=8 ? 'Erg goed' : 'Goed' ?></strong>
                   <span class="score-blauw"><?= $gem ?></span></p>
                <p><?= $aantal ?> beoordelingen</p>
                <p>“<?= htmlspecialchars(mb_strimwidth($eerste['tekst'] ?? '',0,80,'…')) ?>”</p>
                <p><strong><?= htmlspecialchars($eerste['naam'] ?? '') ?></strong></p>
            <?php else: ?>
                <p>Nog geen recensies.</p>
            <?php endif; ?>

        <button class="reserveerknop">Reserveer je verblijf in dit appartement<br>€500 p.p.</button>

        <div class="recensieblok">
            <p><strong>Erg goed</strong> <span class="score-blauw">8,5</span></p>
            <p>52 beoordelingen</p>
            <p>“De locatie is perfect, 200 m. van het metrostation. Groot balkon.”</p>
            <p><strong>Naam</strong></p>
            <p><strong>Uitstekende locatie!</strong> <span class="score-blauw">9,6</span></p>

        </div>

        <div class="map-vlak">Kaart</div>
    </div>
</div>


<footer><?php include 'footer.php'; ?></footer>

<footer>
    <?php
    include("footer.php");
    ?>
</footer>


</body>
</html>
