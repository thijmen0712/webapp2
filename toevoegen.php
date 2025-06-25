<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // gegevens uit het formulier
    $titel = $_POST['titel'];
    $locatie = $_POST['locatie'];
    $prijs = $_POST['prijs'];
    $luchthaven = $_POST['luchthaven'];
    $locatie_link = $_POST['locatie_link'];
    $afbeelding = $_POST['afbeelding'];
    $afbeelding2 = $_POST['afbeelding2'];
    $afbeelding3 = $_POST['afbeelding3'];
    $afbeelding4 = $_POST['afbeelding4'];
    $afbeelding5 = $_POST['afbeelding5'];
    $ontbijt = $_POST['ontbijt'];
    $wifi = $_POST['wifi'];
    $zwembad = $_POST['zwembad'];
    $restaurant = $_POST['restaurant'];
    $wellness = $_POST['wellness'];
    $badkamer = $_POST['badkamer'];
    $huisdieren = $_POST['huisdieren'];
    $beschrijving_lang = $_POST['beschrijving_lang'];

    // query uitvoeren
    $sql = "INSERT INTO reizen (
        titel, locatie, prijs, luchthaven, locatie_link,
        afbeelding, afbeelding2, afbeelding3, afbeelding4, afbeelding5,
        ontbijt, wifi, zwembad, restaurant, wellness,
        badkamer, huisdieren, beschrijving_lang
    ) VALUES (
        :titel, :locatie, :prijs, :luchthaven, :locatie_link,
        :afbeelding, :afbeelding2, :afbeelding3, :afbeelding4, :afbeelding5,
        :ontbijt, :wifi, :zwembad, :restaurant, :wellness,
        :badkamer, :huisdieren, :beschrijving_lang
    )";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':titel' => $titel,
        ':locatie' => $locatie,
        ':prijs' => $prijs,
        ':luchthaven' => $luchthaven,
        ':locatie_link' => $locatie_link,
        ':afbeelding' => $afbeelding,
        ':afbeelding2' => $afbeelding2,
        ':afbeelding3' => $afbeelding3,
        ':afbeelding4' => $afbeelding4,
        ':afbeelding5' => $afbeelding5,
        ':ontbijt' => $ontbijt,
        ':wifi' => $wifi,
        ':zwembad' => $zwembad,
        ':restaurant' => $restaurant,
        ':wellness' => $wellness,
        ':badkamer' => $badkamer,
        ':huisdieren' => $huisdieren,
        ':beschrijving_lang' => $beschrijving_lang
    ]);

    // ✅ terug naar admin.php
    header("Location: admin.php");
    exit;
}
?>

<!-- HTML FORMULIER -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Reis toevoegen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Reis Toevoegen</h2>

<form method="post">
    <!-- hier komen je inputvelden, bv: -->
    <label>Titel</label>
    <input type="text" name="titel" required><br>

    <label>Locatie</label>
    <input type="text" name="locatie" required><br>

    <label>Prijs</label>
    <input type="number" name="prijs" step="0.01" required><br>

    <label>Luchthaven</label>
    <input type="text" name="luchthaven"><br>

    <label>Locatie link</label>
    <input type="text" name="locatie_link"><br>

    <label>Afbeelding 1</label>
    <input type="text" name="afbeelding"><br>

    <label>Afbeelding 2</label>
    <input type="text" name="afbeelding2"><br>

    <label>Afbeelding 3</label>
    <input type="text" name="afbeelding3"><br>

    <label>Afbeelding 4</label>
    <input type="text" name="afbeelding4"><br>

    <label>Afbeelding 5</label>
    <input type="text" name="afbeelding5"><br>

	<div>1 = ja, 0 = nee</div>
	
    <label>Ontbijt (ja/nee)</label>
    <input type="text" name="ontbijt"><br>

    <label>Wifi (ja/nee)</label>
    <input type="text" name="wifi"><br>

    <label>Zwembad (ja/nee)</label>
    <input type="text" name="zwembad"><br>

    <label>Restaurant (ja/nee)</label>
    <input type="text" name="restaurant"><br>

    <label>Wellness (ja/nee)</label>
    <input type="text" name="wellness"><br>

    <label>Badkamer (ja/nee)</label>
    <input type="text" name="badkamer"><br>

    <label>Huisdieren toegestaan (ja/nee)</label>
    <input type="text" name="huisdieren"><br>

    <label>Volledige Beschrijving</label><br>
    <textarea name="beschrijving_lang" rows="5"></textarea><br>

    <button type="submit">Toevoegen</button>
</form>

<p><a href="admin.php">Terug naar Admin</a></p>

</body>
</html>
