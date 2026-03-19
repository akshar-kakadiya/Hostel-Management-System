<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=leave_requests.xls");
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

echo "ID\tStudent ID\tStart Date\tEnd Date\tReason\tStatus\n";

$query = "SELECT * FROM leave_requests";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t" . $row['student_id'] . "\t" . $row['start_date'] . "\t" . $row['end_date'] . "\t" . $row['reason'] . "\t" . $row['status'] . "\n";
}

$conn->close();
?>
