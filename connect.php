<?php
$host = 'localhost:3306';
$db   = 'h_00092f1b_webapp2';
$user = 'h_00092f1b_thijmen';
$pass = 'z54h7H%2f';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connectie mislukt: " . $e->getMessage());
}
?>
