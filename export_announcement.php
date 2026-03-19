<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=announcements.xls");
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

echo "ID\tTitle\tMessage\tStudent ID\tCreated At\n";

// Query to fetch all announcements
$query = "SELECT * FROM notifications";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t" . $row['title'] . "\t" . $row['message'] . "\t" . $row['student_id'] . "\t" . $row['date_time'] . "\n";
}

$conn->close();
?>
