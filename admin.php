<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT rol FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $user['rol'] !== 'admin') {
    header('Location: inloggen.php');
    exit();
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="admin-lijst">
    <h2>Welkom op de adminpagina</h2>
    <p>Wat wil je doen?</p>

    <div class="admin-buttons">
        <a href="toevoegen.php" class="admin-button">➕ Reis toevoegen</a>
        <a href="bewerk-lijst.php" class="admin-button">✏️ Reis bewerken</a>
        <a href="verwijder-lijst.php" class="admin-button">🗑️ Reis verwijderen</a>
        <a href="reserveringen.php" class="admin-button">📄 Reserveringen bekijken</a>
		<a href="recenties-beheer.php" class="admin-button">📄 recenties beheren</a>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
