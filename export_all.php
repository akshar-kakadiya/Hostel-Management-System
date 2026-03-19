<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=full_report.xls");
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

// Create a function to export data for each table
function exportTable($conn, $query, $sheetName) {
    $result = $conn->query($query);
    $data = [];
    
    if ($result->num_rows > 0) {
        // Fetch column names
        $columns = [];
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            $columns[] = $field->name;
        }
        $data[] = $columns;
        
        // Fetch table rows
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    echo "<br/><br/><strong>$sheetName</strong><br/>";
    echo implode("\t", $columns) . "\n";  // Column headers
    foreach ($data as $row) {
        echo implode("\t", $row) . "\n";  // Data rows
    }
}

// Export data for each table
exportTable($conn, "SELECT * FROM student_log", "Student Log");
exportTable($conn, "SELECT * FROM fees", "Fees");
exportTable($conn, "SELECT * FROM complaints", "Complaints");
exportTable($conn, "SELECT * FROM leave_requests", "Leave Requests");

$conn->close();
?>
