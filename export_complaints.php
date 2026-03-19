<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=complaints.xls");
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

echo "ID\tStudent ID\tStudent Name\tComplaint\tStatus\tCreated At\n";

$query = "SELECT * FROM complaints";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t" . $row['student_id'] . "\t" . $row['student_name'] . "\t" . $row['complaint'] . "\t" . $row['status'] . "\t" . $row['created_at'] . "\n";
}

$conn->close();
?>
