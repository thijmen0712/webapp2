<?php
include 'connect.php';

$sql = "SELECT * FROM reizen";
$stmt = $conn->query($sql);
$reizen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Reizen Bewerken</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>
	
	
	
	
    <div class="pagina-container">
        <h1>Reizen Bewerken</h1>
        <table>
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Locatie</th>
                    <th>Prijs</th>
                    <th>Bewerk</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reizen as $reis): ?>
                    <tr>
                        <td><?= htmlspecialchars($reis['titel']) ?></td>
                        <td><?= htmlspecialchars($reis['locatie']) ?></td>
                        <td>€<?= number_format($reis['prijs'], 2, ',', '.') ?></td>
                        <td><a href="reis-bewerken.php?id=<?= $reis['id'] ?>" class="btn">Bewerk</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
		<a href="admin.php">Terug naar Admin</a>
    </div>
	
	<?php include 'footer.php'; ?>
	
</body>
</html>