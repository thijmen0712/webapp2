<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Recensies beheren</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'connect.php'; ?>

<?php
// Verwerken van akkoord of afkeuren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['recensie_id'];

    if ($_POST['actie'] === 'akkoord') {
        $stmt = $conn->prepare("UPDATE recensies SET goedgekeurd = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif ($_POST['actie'] === 'afkeuren') {
        $stmt = $conn->prepare("DELETE FROM recensies WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

// hiermee toont die de recenties die eig nog niet goedgekeurd zijn
$stmt = $conn->query("
    SELECT r.id, r.beoordeling, r.tekst, r.datum, r.goedgekeurd, u.naam 
    FROM recensies r
    JOIN users u ON u.id = r.user_id
    WHERE r.goedgekeurd = 0
    ORDER BY r.datum DESC
");
$recensies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 style="text-align:center;">Recensies beheren</h1>

<div class="recensie-lijst">
    <?php if (empty($recensies)): ?>
        <p>Er zijn geen nieuwe recensies meer om te keuren.</p>
    <?php else: ?>
        <?php foreach ($recensies as $r): ?>
            <div class="recensie">
                <p>Naam: <?= $r['naam'] ?></p>
                <p>Beoordeling: <?= $r['beoordeling'] ?>/5</p>
                <p>Tekst: <?= $r['tekst'] ?></p>
                <p>Datum: <?= $r['datum'] ?></p>
                <form method="post">
                    <input type="hidden" name="recensie_id" value="<?= $r['id'] ?>">
                    <button type="submit" name="actie" value="akkoord">Akkoord</button><br>
                    <button type="submit" name="actie" value="afkeuren">Afkeuren</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
