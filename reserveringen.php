<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$stmt = $conn->prepare("SELECT rol FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['rol'] !== 'admin') {
    header('Location: inloggen.php');
    exit();
}

$stmt = $conn->query("
SELECT rb.id AS reis_boeking_id, u.naam, r.titel, b.boekdatum, b.vertrekdatum, b.status
FROM reis_boekingen rb
JOIN boekingen b ON rb.boeking_id = b.id
JOIN users u ON b.user_id = u.id
JOIN reizen r ON rb.reis_id = r.id
ORDER BY b.boekdatum DESC
");
$reserveringen = $stmt->fetchAll();
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Reserveringen Overzicht</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="admin-lijst">
    <h2>Alle Reserveringen</h2>

    <?php if (count($reserveringen) > 0): ?>
        <table>
            <tr>
                <th>Naam</th>
                <th>Reis</th>
                <th>Boekdatum</th>
                <th>Vertrekdatum</th>
                <th>Status</th>
                <th>Actie</th>
            </tr>
            <?php foreach ($reserveringen as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['naam']) ?></td>
                    <td><?= htmlspecialchars($res['titel']) ?></td>
                    <td><?= htmlspecialchars($res['boekdatum']) ?></td>
                    <td><?= htmlspecialchars($res['vertrekdatum']) ?></td>
                    <td><?= htmlspecialchars($res['status']) ?></td>
                    <td>
                        <?php if ($res['status'] !== 'geannuleerd'): ?>
                            <form method="post" action="annuleer.php">
                                <input type="hidden" name="reis_boeking_id" value="<?= $res['reis_boeking_id'] ?>">
                                <button type="submit">Annuleer</button>
                            </form>
                        <?php else: ?>
                            <em>Al geannuleerd</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Geen reserveringen gevonden.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
