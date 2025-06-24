<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $naam = $_POST['naam'];
    $email = $_POST['email'];

    if ($naam == '' || $email == '') {
        header('Location: account.php?fout=leeg');
        exit();
    }

    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $id]);

    if ($stmt->rowCount() > 0) {
        header('Location: account.php?fout=email');
        exit();
    }

    $sql = "UPDATE users SET naam = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$naam, $email, $id]);

    header('Location: account.php?ok=1');
    exit();
}

header('Location: account.php');
exit();
?>
