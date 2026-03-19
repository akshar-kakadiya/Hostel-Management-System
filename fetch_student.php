<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = intval($_POST['student_id']);

    $sql = "SELECT * FROM student_log WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(array_merge(["status" => "success"], $row));
    } else {
        echo json_encode(["status" => "error", "message" => "No student found"]);
    }

    $stmt->close();
}

$conn->close();
?>
