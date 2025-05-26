<?php
global $conn;
include("connect.php");


$sql = "SELECT * FROM test";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo $row['text'] . "<br>";
}

$conn->close();
?>