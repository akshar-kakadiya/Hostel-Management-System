<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=fees.xls");
header("Pragma: no-cache");
header("Expires: 0");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "ID\tStudent ID\tAmount\tDate\n";

$query = "SELECT * FROM fees";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t" . $row['student_id'] . "\t" . $row['amount'] . "\t" . $row['date'] . "\n";
}

$conn->close();
?>
