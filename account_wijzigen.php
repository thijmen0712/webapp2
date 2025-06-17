<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam  = trim($_POST['naam']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($naam === '' || $email === '') {
        header('Location: account.php?fout=leeg');
        exit();
    }

    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? AND id <> ?');
    $stmt->execute([$email, $id]);

    if ($stmt->rowCount() > 0) {
        header('Location: account.php?fout=email');
        exit();
    }

    $stmt = $conn->prepare('UPDATE users SET naam = ?, email = ? WHERE id = ?');
    $stmt->execute([$naam, $email, $id]);

    header('Location: account.php?ok=1');
    exit();
}

header('Location: account.php');
exit();
?>
