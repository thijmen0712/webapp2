<?php
session_start();
include 'connect.php';

$naam = $_POST['naam'] ?? '';
$email = $_POST['email'] ?? '';
$wachtwoord = $_POST['new-password'] ?? '';
$bevestig = $_POST['confirm-password'] ?? '';

if ($wachtwoord !== $bevestig) {
    echo "Wachtwoorden komen niet overeen.";
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$bestaat = $stmt->fetch();

if ($bestaat) {
    echo "E-mailadres is al in gebruik.";
    exit;
}

$hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (naam, email, wachtwoord) VALUES (?, ?, ?)");
$stmt->execute([$naam, $email, $hash]);

$_SESSION['user_id'] = $conn->lastInsertId();

header('Location: account.php');
exit;
?>
