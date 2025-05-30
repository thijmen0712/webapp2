<?php
include 'connect.php';

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
</head>

<body>
    <?php
    include 'header.php';
    ?>

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
                    <div class="bestemming">
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
                    </div>
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