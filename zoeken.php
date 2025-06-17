<?php
include 'connect.php';

// Filters ophalen
$van = $_GET['van'] ?? '';
$naar = $_GET['naar'] ?? '';
$minprijs = $_GET['minprijs'] ?? 0;
$maxprijs = $_GET['maxprijs'] ?? 2000;
$actieveBestemmingen = $_GET['bestemmingen'] ?? [];

// Bestemmingen + aantallen ophalen
$bestemmingQuery = $conn->query("SELECT locatie, COUNT(*) as aantal FROM reizen GROUP BY locatie ORDER BY locatie");
$bestemmingen = $bestemmingQuery->fetchAll(PDO::FETCH_ASSOC);

// SQL-query voorbereiden
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

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$van = $_GET['van'] ?? '';
$naar = $_GET['naar'] ?? '';

$sql = "SELECT locatie, titel, luchthaven, prijs, afbeelding FROM reizen WHERE luchthaven LIKE :van AND (titel LIKE :naar OR locatie LIKE :naar)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':van' => "%$van%",
    ':naar' => "%$naar%"
]);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <title>Zoekresultaten</title>
    <link rel="stylesheet" href="css/style.css" />
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

        <!-- ✅ FILTER SIDEBAR -->
        <form method="GET" action="zoeken.php" class="filter-sidebar">
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

            <div class="filter-section">
                <h3 onclick="toggleSection('filter-prijs')">Prijs ⌄</h3>
                <div id="filter-prijs" class="filter-content active">
                    <label>Min prijs: <input type="number" name="minprijs" value="<?php echo htmlspecialchars($minprijs); ?>"></label><br>
                    <label>Max prijs: <input type="number" name="maxprijs" value="<?php echo htmlspecialchars($maxprijs); ?>"></label>
                </div>
            </div>

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

            <button type="submit">Toepassen</button>
        </form>

        <!-- ✅ RESULTATEN -->
        <section class="bestemmingen">
            <a href="index.php" class="terug-knop">Terug</a>
            <h1>Zoekresultaten</h1>

            <?php
            $count = 0;
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result && count($result) > 0) {
                echo '<div class="bestemmingen-flex">';
                foreach ($result as $row) {
            ?>
                    <div class="bestemmingen-container">
                        <a href="reis.php?id=<?php echo $row['id']; ?>" class="bestemming">
                            <div class="banner">
                                <img src="images/<?php echo htmlspecialchars($row['afbeelding']); ?>" alt="<?php echo htmlspecialchars($row['locatie']); ?>">
                            </div>
                            <h2><?php echo htmlspecialchars($row['locatie']); ?></h2>
                            <b><?php echo htmlspecialchars($row['titel']); ?></b>
                            <div class="bestemming-info">
                                <img src="images/luchthaven.png" alt="luchthaven">
                                <p><?php echo htmlspecialchars($row['luchthaven']); ?></p>
                            </div>
                            <p class="prijs">Vanaf <span>€<?php echo htmlspecialchars($row['prijs']); ?></span></p>
                        </a>
                    </div>
            <?php
                    $count++;
                    if ($count % 4 == 0) {
                        echo '</div><div class="bestemmingen-flex">';
                    }
                }
                echo '</div>';
            } else {
                echo "<p>Geen reizen gevonden.</p>";
            }
            ?>
        </section>
            </main>

            <div class="disclamer">
                <div class="i"><b>i</b></div>
                <p>
                    Alle getoonde prijzen zijn vanaf-prijzen op basis van een enkele reis. De prijzen kunnen wijzigen en zijn afhankelijk van de beschikbaarheid van het tarief.
                    Belastingen en toeslagen zijn inbegrepen, maar er kan wel een toeslag voor betaling in rekening worden gebracht. Je ziet de definitieve ticketprijs als je de
                    betaling bent gestart.
                </p>
            </div>

            <?php include 'footer.php'; ?>
</body>

</html>
<div class="disclamer">
    <div class="i">
        <b>i</b>
    </div>
    <p>
        Alle getoonde prijzen zijn vanaf-prijzen op basis van een enkele reis. De prijzen kunnen wijzigen en zijn afhankelijk van de beschikbaarheid van het tarief.
        Belastingen en toeslagen zijn inbegrepen, maar er kan wel een toeslag voor betaling in rekening worden gebracht. Je ziet de definitieve ticketprijs als je de
        betaling bent gestart.
    </p>
</div>

<?php
include 'footer.php';
?>
</body>

</html>
