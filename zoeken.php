<?php
include 'connect.php';

$van = $_GET['van'] ?? '';
$naar = $_GET['naar'] ?? '';

$sql = "SELECT * FROM reizen WHERE luchthaven LIKE :van AND titel LIKE :naar";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':van' => "%$van%",
    ':naar' => "%$naar%"
]);

echo "<h2>Resultaten:</h2>";
while ($row = $stmt->fetch()) {
    echo "<p>Reis naar <strong>" . $row['titel'] . "</strong> vanaf <strong>" . $row['luchthaven'] . "</strong> – €" . $row['prijs'] . "</p>";
}
?>
