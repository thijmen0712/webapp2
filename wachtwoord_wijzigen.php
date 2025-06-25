<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $huidig = $_POST['huidig_wachtwoord'] ?? '';
    $nieuw = $_POST['nieuw_wachtwoord'] ?? '';
    $herhaal = $_POST['herhaal_wachtwoord'] ?? '';

    if ($nieuw !== $herhaal) {
        echo "Nieuw wachtwoord komt niet overeen.";
        exit;
    }

    // Huidig wachtwoord ophalen (hash)
    $stmt = $conn->prepare("SELECT wachtwoord FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($huidig, $user['wachtwoord'])) {
        echo "Huidig wachtwoord is onjuist.";
        exit;
    }

    // Nieuw wachtwoord hashen
    $nieuwHash = password_hash($nieuw, PASSWORD_DEFAULT);

    // Updaten in database
    $stmt = $conn->prepare("UPDATE users SET wachtwoord = ? WHERE id = ?");
    $stmt->execute([$nieuwHash, $user_id]);

    header('Location: account.php?message=wachtwoord_gewijzigd');
    exit();
}
?>
