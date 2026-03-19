<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=student_log.xls");
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

echo "ID\tName\tEmail\tGuardian Name\tGuardian Mobile\tUser Mobile\tCourse\tCollege Year\tCollege Name\tBirthday\tAge\tAddress\tStarting Date\tStatus\tRoom Number\n";

$query = "SELECT * FROM student_log";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t" . $row['name'] . "\t" . $row['email'] . "\t" . $row['guardian_name'] . "\t" . $row['guardian_mobile'] . "\t" . $row['user_mobile'] . "\t" . $row['course'] . "\t" . $row['college_year'] . "\t" . $row['college_name'] . "\t" . $row['birthday'] . "\t" . $row['age'] . "\t" . $row['address'] . "\t" . $row['starting_date'] . "\t" . $row['status'] . "\t" . $row['room_number'] . "\n";
}

$conn->close();
?>
