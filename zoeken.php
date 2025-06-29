<?php
include 'connect.php';

$van = $_GET['van'] ?? '';
$naar = $_GET['naar'] ?? '';
$minprijs = $_GET['minprijs'] ?? 0;
$maxprijs = $_GET['maxprijs'] ?? 2000;
$startDatum = $_GET['startdatum'] ?? '';
$eindDatum = $_GET['einddatum'] ?? '';
$actieveBestemmingen = $_GET['bestemmingen'] ?? [];

// de bestemming ophalen.
$bestemmingQuery = $conn->query("SELECT locatie, COUNT(*) as aantal FROM reizen GROUP BY locatie ORDER BY locatie");
$bestemmingen = $bestemmingQuery->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT id, locatie, titel, luchthaven, prijs, afbeelding FROM reizen WHERE prijs BETWEEN ? AND ?";
$params = [$minprijs, $maxprijs];

if (!empty($van)) {
    $sql .= " AND luchthaven LIKE ?";
    $params[] = "%$van%";
}

if (!empty($naar)) {
    $sql .= " AND (titel LIKE ? OR locatie LIKE ?)";
    $params[] = "%$naar%";
    $params[] = "%$naar%";
}

if (!empty($actieveBestemmingen)) {
    $in = implode(',', array_fill(0, count($actieveBestemmingen), '?'));
    $sql .= " AND locatie IN ($in)";
    foreach ($actieveBestemmingen as $b) {
        $params[] = $b;
    }
}

// De beschikbaarheid controleren op vertrek en retourdatum
if (!empty($startDatum) && !empty($eindDatum)) {
    $sql .= " AND id NOT IN (
        SELECT reis_id
        FROM reis_boekingen rb
        JOIN boekingen b ON b.id = rb.boeking_id
        WHERE b.status = 'bevestigd'
        AND NOT (
            b.retourdatum < ? OR b.vertrekdatum > ?
        )
    )";
    $params[] = $startDatum;
    $params[] = $eindDatum;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Zoekresultaten</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function toggleSection(id) {
            const section = document.getElementById(id);
            section.classList.toggle('active');
        }
    </script>
</head>
<body>
<?php include 'header.php'; ?>

<main class="zoekpagina">

    <form method="GET" action="zoeken.php" class="filter-sidebar">

        <!-- Bestemmingen -->
        <div class="filter-section">
            <h3 onclick="toggleSection('filter-bestemmingen')">Bestemmingen ⌄</h3>
            <div id="filter-bestemmingen" class="filter-content active">
                <?php foreach ($bestemmingen as $b):
                    $loc = htmlspecialchars($b['locatie']);
                    $checked = in_array($b['locatie'], $actieveBestemmingen) ? 'checked' : '';
                    ?>
                    <label>
                        <input type="checkbox" name="bestemmingen[]" value="<?php echo $loc; ?>" <?php echo $checked; ?>>
                        <?php echo $loc; ?>
                        <span style="float:right; color:gray;"><?php echo $b['aantal']; ?></span>
                    </label><br>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Prijs -->
        <div class="filter-section">
            <h3 onclick="toggleSection('filter-prijs')">Prijs ⌄</h3>
            <div id="filter-prijs" class="filter-content active">
                <label>Min prijs: <input type="number" name="minprijs" value="<?= htmlspecialchars($minprijs); ?>"></label><br>
                <label>Max prijs: <input type="number" name="maxprijs" value="<?= htmlspecialchars($maxprijs); ?>"></label>
            </div>
        </div>

        <!-- Voorzieningen -->
        <div class="filter-section">
            <h3 onclick="toggleSection('filter-voorzieningen')">Voorzieningen ⌄</h3>
            <div id="filter-voorzieningen" class="filter-content active">
                <label><input type="checkbox" name="voorzieningen[]" value="Ontbijt"> Ontbijt</label><br>
                <label><input type="checkbox" name="voorzieningen[]" value="Wifi"> Wifi</label><br>
                <label><input type="checkbox" name="voorzieningen[]" value="Zwembad"> Zwembad</label><br>
                <label><input type="checkbox" name="voorzieningen[]" value="Restaurant"> Restaurant</label><br>
                <label><input type="checkbox" name="voorzieningen[]" value="Wellness"> Wellness</label><br>
                <label><input type="checkbox" name="voorzieningen[]" value="Badkamer"> Badkamer</label><br>
                <label><input type="checkbox" name="voorzieningen[]" value="Huisdieren toegestaan"> Huisdieren toegestaan</label>
            </div>
        </div>

        <!-- Datum -->
        <div class="filter-section">
            <h3 onclick="toggleSection('filter-datum')">Datum ⌄</h3>
            <div id="filter-datum" class="filter-content active">
                <label>Vertrekdatum</label>
                <input type="date" name="startdatum" value="<?= htmlspecialchars($startDatum) ?>"><br>
                <label>Retourdatum</label>
                <input type="date" name="einddatum" value="<?= htmlspecialchars($eindDatum) ?>">
            </div>
        </div>

        <button type="submit">Toepassen</button>
    </form>

    <section class="bestemmingen">
        <a href="index.php" class="terug-knop">Terug</a>
        <h1>Zoekresultaten</h1>

        <?php if ($result): ?>
            <div class="bestemmingen-flex">
                <?php foreach ($result as $row): ?>
                    <div class="bestemmingen-container">
                        <a href="reis.php?id=<?= $row['id']; ?>" class="bestemming">
                            <div class="banner">
                                <img src="images/<?= htmlspecialchars($row['afbeelding']); ?>" alt="<?= htmlspecialchars($row['locatie']); ?>">
                            </div>
                            <h2><?= htmlspecialchars($row['locatie']); ?></h2>
                            <b><?= htmlspecialchars($row['titel']); ?></b>
                            <div class="bestemming-info">
                                <img src="images/luchthaven.png" alt="luchthaven">
                                <p><?= htmlspecialchars($row['luchthaven']); ?></p>
                            </div>
                            <p class="prijs">Vanaf <span>€<?= htmlspecialchars($row['prijs']); ?></span></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Geen reizen gevonden.</p>
        <?php endif; ?>
    </section>

</main>

<?php include 'footer.php'; ?>
</body>
</html>
