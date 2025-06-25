<?php
include 'connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Ongeldige reis.";
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM reizen WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$reis = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reis) {
    echo "Reis niet gevonden.";
    exit;
}

$sqlRec = "SELECT r.beoordeling, r.tekst, r.datum, u.naam 
           FROM recensies r 
           JOIN users u ON r.user_id = u.id 
           WHERE r.reis_id = :reis_id AND r.goedgekeurd = 1 
           ORDER BY r.datum DESC";
$stmtRec = $conn->prepare($sqlRec);
$stmtRec->execute(['reis_id' => $reis['id']]);
$recensies = $stmtRec->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($reis['titel']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

	<?php include 'header.php'; ?>
	
	<a href="javascript:history.back()" class="terug-knop">Terug</a>

<div class="pagina-container">

    <div class="header">
        <h1><?php echo htmlspecialchars($reis['titel']); ?></h1>
        <p><?php echo htmlspecialchars($reis['locatie']); ?> – 
            <a href="<?php echo $reis['locatie_link']; ?>" target="_blank" class="toon-op-kaart">Toon op kaart</a>
        </p>
    </div>

	



<div class="foto-grid">
    <?php if (!empty($reis['afbeelding'])): ?>
        <img src="images/<?php echo $reis['afbeelding']; ?>">
    <?php endif; ?>
    <?php if (!empty($reis['afbeelding2'])): ?>
        <img src="images/<?php echo $reis['afbeelding2']; ?>">
    <?php endif; ?>
    <?php if (!empty($reis['afbeelding3'])): ?>
        <img src="images/<?php echo $reis['afbeelding3']; ?>">
    <?php endif; ?>
    <?php if (!empty($reis['afbeelding4'])): ?>
        <img src="images/<?php echo $reis['afbeelding4']; ?>">
    <?php endif; ?>
    <?php if (!empty($reis['afbeelding5'])): ?>
        <img src="images/<?php echo $reis['afbeelding5']; ?>">
    <?php endif; ?>
</div>


    <div class="info-blokken">

        <div class="infomatiereis">
            <div class="faciliteiten">
                <?php if ($reis['ontbijt']) echo '<div>🍽️ All inclusive</div>'; ?>
                <?php if ($reis['wifi']) echo '<div>📶 Gratis WiFi</div>'; ?>
                <?php if ($reis['zwembad']) echo '<div>🏊‍♂️ Binnenzwembad</div>'; ?>
                <?php if ($reis['restaurant']) echo '<div>🍴 Restaurant</div>'; ?>
                <?php if ($reis['wellness']) echo '<div>💆 Wellnesscentrum</div>'; ?>
                <?php if ($reis['badkamer']) echo '<div>🛁 Eigen badkamer</div>'; ?>
                <?php if ($reis['huisdieren']) echo '<div>🐶 Huisdieren toegestaan</div>'; ?>
            </div>

            <div class="recensies">
                <h3>Wat onze reizigers vinden:</h3>
                <?php
                if (!$recensies) {
                    echo "<p>Er zijn nog geen recensies.</p>";
                } else {
                    foreach ($recensies as $recensie) {
                        $letter = strtoupper(substr($recensie['naam'], 0, 1));
						$ster = str_repeat('★', (int)$recensie['beoordeling'])
							  . str_repeat('☆', 5 - (int)$recensie['beoordeling']);

						echo "<div class='recensie'>";
						echo "<div class='recensie-naam'>";
						echo "<div class='profiel'>$letter</div>";
						echo "<div>";
						echo "<div>" . htmlspecialchars($recensie['naam']) . "</div>";
						echo "<div>" . htmlspecialchars($recensie['datum']) . "</div>";
						echo "<div>$ster</div>";
						echo "</div>";
						echo "</div>";
						echo "<p>" . nl2br(htmlspecialchars($recensie['tekst'])) . "</p>";
						echo "</div>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="beschrijving">
            <h2>Beschrijving</h2>
            <p><?php echo nl2br(htmlspecialchars($reis['beschrijving_lang'])); ?></p>
        </div>
    </div>

    <div class="prijs-boek">
        <p><strong>Vanaf €<?php echo $reis['prijs']; ?></strong> per nacht</p>
        <a href="boeken.php?id=<?php echo $reis['id']; ?>" class="boek-knop">Boek nu</a>
    </div>

</div>

</body>
</html>
