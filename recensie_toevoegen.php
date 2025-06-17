<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$reis_id = $_POST['reis_id'] ?? null;
$beoordeling = $_POST['beoordeling'] ?? null;
$tekst = $_POST['tekst'] ?? '';

if (!$reis_id || !$beoordeling || $tekst === '') {
    die('Alle velden zijn verplicht.');
}

// check of reis bestaat
$stmt = $conn->prepare("SELECT id FROM reizen WHERE id = ?");
$stmt->execute([$reis_id]);
if ($stmt->rowCount() === 0) {
    die('Reis bestaat niet.');
}

// insert recensie
$stmt = $conn->prepare("
    INSERT INTO recensies (user_id, reis_id, beoordeling, tekst, goedgekeurd, datum)
    VALUES (?, ?, ?, ?, 1, NOW())
");

$stmt->execute([$user_id, $reis_id, $beoordeling, $tekst]);

header("Location: account.php?recensie=ok");
exit();
