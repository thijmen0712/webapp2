<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        'titel' => $_POST['titel'],
        'locatie' => $_POST['locatie'],
        'prijs' => $_POST['prijs'],
        'luchthaven' => $_POST['luchthaven'],
        'locatie_link' => $_POST['locatie_link'],
        'afbeelding' => $_POST['afbeelding'],
        'afbeelding2' => $_POST['afbeelding2'],
        'afbeelding3' => $_POST['afbeelding3'],
        'afbeelding4' => $_POST['afbeelding4'],
        'afbeelding5' => $_POST['afbeelding5'],
        'ontbijt' => $_POST['ontbijt'],
        'wifi' => $_POST['wifi'],
        'zwembad' => $_POST['zwembad'],
        'restaurant' => $_POST['restaurant'],
        'wellness' => $_POST['wellness'],
        'badkamer' => $_POST['badkamer'],
        'huisdieren' => $_POST['huisdieren'],
        'beschrijving_lang' => $_POST['beschrijving_lang']
    ]);

    header("Location: admin.php");
    exit;
} else {
    echo "Formulier is niet goed verzonden.";
}
