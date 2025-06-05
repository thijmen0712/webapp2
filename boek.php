<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TL reizen - bestemmingen</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    
    <?php
    include 'header.php';
    include 'connect.php';
    ?>
    <div class="bestemmingen-flex">
        <?php
        $sql = "SELECT locatie, titel, luchthaven, prijs, afbeelding FROM reizen";
        $result = $conn->query($sql);

        $count = 0;
        if ($result && $result->rowCount() > 0) {
            echo '<div class="bestemmingen-flex">';
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($count > 0 && $count % 4 == 0) {
                    echo '</div><div class="bestemmingen-flex">';
                }
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
            }
            echo '</div>';
        } else {
            echo "<p>Geen reizen gevonden.</p>";
        }
        ?>
    </div>
    <div class="disclamer">
        <div class="i">
            <b>i</b>
        </div>
        <p>
            Alle prijzen zijn vanaf prijzen en kunnen variëren afhankelijk van de boekingsdatum en beschikbaarheid.
            Controleer altijd de actuele prijzen en voorwaarden op onze website.
        </p>

    </div>
    <?php include 'footer.php'; ?>
    
</body>

=======
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestemmingen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include 'header.php';
?>



<footer>
    <?php
    include ("footer.php");
    ?>
</footer>

</body>
>>>>>>> 3b6bef096e3e0ce0d055f0722bef188c816b950d
</html>