<?php
$host = 'fdb1028.awardspace.net';
$db   = '4637952_webapp2';
$user = '4637952_webapp2';
$pass = 'reisbureau12';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connectie mislukt: " . $e->getMessage());
}
?>
<?php phpinfo(); ?>
