<?php
$type = $_GET['type'] ?? 'van';
$q = $_GET['q'] ?? '';
$q = htmlspecialchars($q);

include 'connect.php';

if ($type === 'van') {
    $stmt = $conn->prepare("SELECT DISTINCT luchthaven FROM reizen WHERE luchthaven LIKE CONCAT('%', :q, '%') LIMIT 10");
} else {
    $stmt = $conn->prepare("SELECT DISTINCT titel FROM reizen WHERE titel LIKE CONCAT('%', :q, '%') LIMIT 10");
}

$stmt->execute(['q' => $q]);
$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = array_values($row)[0];
}

echo json_encode($data);
?>
