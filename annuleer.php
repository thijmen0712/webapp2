<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: inloggen.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$rol_stmt = $conn->prepare("SELECT rol FROM users WHERE id = ?");
$rol_stmt->execute([$user_id]);
$rol_result = $rol_stmt->fetch();
$is_admin = ($rol_result && $rol_result['rol'] === 'admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reis_boeking_id'])) {
    $reis_boeking_id = $_POST['reis_boeking_id'];

    if ($is_admin) {
        // Admin mag elke boeking annuleren
        $stmt = $conn->prepare("
            SELECT b.id 
            FROM reis_boekingen rb
            JOIN boekingen b ON rb.boeking_id = b.id
            WHERE rb.id = ?
        ");
        $stmt->execute([$reis_boeking_id]);
    } else {
        // Gewone gebruiker: alleen eigen boekingen
        $stmt = $conn->prepare("
            SELECT b.id 
            FROM reis_boekingen rb
            JOIN boekingen b ON rb.boeking_id = b.id
            WHERE rb.id = ? AND b.user_id = ?
        ");
        $stmt->execute([$reis_boeking_id, $user_id]);
    }

    $result = $stmt->fetch();

    if ($result) {
        $boekings_id = $result['id'];
        $stmt = $conn->prepare("UPDATE boekingen SET status = 'geannuleerd' WHERE id = ?");
        $stmt->execute([$boekings_id]);

        if ($is_admin) {
            header('Location: reserveringen.php?msg=annulering-gelukt');
        } else {
            header('Location: account.php?msg=annulering-gelukt');
        }
        exit();
    } else {
        header('Location: account.php?msg=geen-rechten');
        exit();
    }
} else {
    header('Location: account.php');
    exit();
}
