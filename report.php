<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ms"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ''; // Initialize message variable

// Fetch Total Rooms
$totalRoomsQuery = "SELECT COUNT(*) as total_rooms FROM rooms";
$totalRoomsResult = $conn->query($totalRoomsQuery);
$totalRoomsRow = $totalRoomsResult->fetch_assoc();

// Fetch Total Students
$totalStudentsQuery = "SELECT COUNT(*) as total_students FROM student_log";
$totalStudentsResult = $conn->query($totalStudentsQuery);
$totalStudentsRow = $totalStudentsResult->fetch_assoc();

// Fetch Total Occupied Rooms
$occupiedRoomsQuery = "SELECT COUNT(DISTINCT room_number) as occupied_rooms FROM student_log WHERE room_number IS NOT NULL";
$occupiedRoomsResult = $conn->query($occupiedRoomsQuery);
$occupiedRoomsRow = $occupiedRoomsResult->fetch_assoc();

// Fetch Unoccupied Rooms
$unoccupiedRoomsQuery = "SELECT COUNT(*) as unoccupied_rooms FROM rooms WHERE room_no NOT IN (SELECT DISTINCT room_number FROM student_log WHERE room_number IS NOT NULL)";
$unoccupiedRoomsResult = $conn->query($unoccupiedRoomsQuery);
$unoccupiedRoomsRow = $unoccupiedRoomsResult->fetch_assoc();

// Fetch Students Assigned to Rooms
$studentsWithRoomsQuery = "SELECT s.id, s.name, s.course, r.room_no, r.type FROM student_log s LEFT JOIN rooms r ON s.room_number = r.room_no WHERE s.room_number IS NOT NULL";
$studentsWithRoomsResult = $conn->query($studentsWithRoomsQuery);

// Fetch Students Without Rooms
$studentsWithoutRoomsQuery = "SELECT id, name, course FROM student_log WHERE room_number IS NULL";
$studentsWithoutRoomsResult = $conn->query($studentsWithoutRoomsQuery);

// Fetch Leave Requests
$leaveRequestsQuery = "SELECT l.id, s.name, l.start_date, l.end_date, l.status FROM leave_requests l JOIN student_log s ON l.student_id = s.id";
$leaveRequestsResult = $conn->query($leaveRequestsQuery);

// Fetch Total Fees Collection
$totalFeesQuery = "SELECT SUM(amount) as total_fees FROM fees";
$totalFeesResult = $conn->query($totalFeesQuery);
$totalFeesRow = $totalFeesResult->fetch_assoc();

// Fetch Fees Information
$feesQuery = "SELECT s.name, f.amount, f.date FROM fees f JOIN student_log s ON f.student_id = s.id";
$feesResult = $conn->query($feesQuery);

// Fetch Complaints Information
$complaintsQuery = "SELECT s.name, c.complaint, c.status FROM complaints c JOIN student_log s ON c.student_id = s.id";
$complaintsResult = $conn->query($complaintsQuery);



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Report - Hostel Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: rgb(249, 250, 251);
            color: rgb(17, 24, 39);
            line-height: 1.5;
        }
        .sidebar {
            width: 256px;
            background-color: white;
            border-right: 1px solid rgb(229, 231, 235);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }
        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, rgb(79, 70, 229), rgb(98, 89, 255));
            color: white;
        }
        .sidebar-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .sidebar-nav {
            flex: 1;
            padding: 1rem;
        }
        .nav-item {
            width: 100%;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            color: rgb(75, 85, 99);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .nav-item:hover {
            background: linear-gradient(135deg, rgb(220, 103, 103), rgb(133, 133, 133));
            color: white;
            transform: translateX(5px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgb(79, 70, 229), rgb(98, 89, 255));
            color: white;
        }
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgb(229, 231, 235);
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }

        h2, h3 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 10px 15px;
            font-size: 1rem;
            color: white;
            background-color: rgb(79, 70, 229);
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: rgb(98, 89, 255);
        }

        .report-table {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="sidebar">
        <div class="sidebar-header"><h1>Hostel Management</h1></div>
        <div class="sidebar-nav">
            <a href="dashboard.php" class="nav-item">Dashboard</a>
            <a href="student.php" class="nav-item">Students Management</a>
            <a href="fees.php" class="nav-item">Fees Management</a>
            <a href="rooms.php" class="nav-item">Room Management</a>
            <a href="leave.php" class="nav-item">Leave</a>
            <a href="complaints.php" class="nav-item">Complaints Management</a>
            <a href="notification.php" class="nav-item ">Announcement</a>
            <a href="report.php" class="nav-item active">Reports</a>
            <a href="index.php" class="nav-item">Logout</a>
        </div>
    </div>
<div class="content">
    <h2>Admin Reports</h2>

    <!-- General Report -->
    <h3>General Report</h3>
    <table>
        <tr>
            <th>Total Rooms</th>
            <td><?= $totalRoomsRow['total_rooms'] ?></td>
        </tr>
        <tr>
            <th>Total Students</th>
            <td><?= $totalStudentsRow['total_students'] ?></td>
        </tr>
        <tr>
            <th>Occupied Rooms</th>
            <td><?= $occupiedRoomsRow['occupied_rooms'] ?></td>
        </tr>
        <tr>
            <th>Unoccupied Rooms</th>
            <td><?= $unoccupiedRoomsRow['unoccupied_rooms'] ?></td>
        </tr>
        <tr>
            <th>Total fees collection</th>
            <td>₹<?= $totalFeesRow['total_fees'] ?></td>
        </tr>
    </table>

    <!-- Students Assigned to Rooms -->
    <h3>Students Assigned to Rooms <br></h3>
    <table class="report-table">
        <tr>
            <th>Sr. No</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Course</th>
            <th>Room No</th>
            <th>Room Type</th>
        </tr>
        <?php
        $sr_no = 1;
        while ($row = $studentsWithRoomsResult->fetch_assoc()) { ?>
        <tr>
            <td><?= $sr_no++ ?></td>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['course'] ?></td>
            <td><?= $row['room_no'] ?></td>
            <td><?= $row['type'] ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Students Without Rooms -->
    <h3>Students Without Rooms</h3>
    <table class="report-table">
        <tr>
            <th>Sr. No</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Course</th>
        </tr>
        <?php
        $sr_no = 1;
        while ($row = $studentsWithoutRoomsResult->fetch_assoc()) { ?>
        <tr>
            <td><?= $sr_no++ ?></td>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['course'] ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Leave Requests -->
    <h3>Leave Requests <br></h3>
    <table class="report-table">
        <tr>
            <th>Sr. No</th>
            <th>Student Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
        </tr>
        <?php
        $sr_no = 1;
        while ($row = $leaveRequestsResult->fetch_assoc()) { ?>
        <tr>
            <td><?= $sr_no++ ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['start_date'] ?></td>
            <td><?= $row['end_date'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Fees Report -->
    <h3>Fees Report <br></h3>
    <table class="report-table">
        <tr>
            <th>Sr. No</th>
            <th>Student Name</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        <?php
        $sr_no = 1;
        while ($row = $feesResult->fetch_assoc()) { ?>
        <tr>
            <td><?= $sr_no++ ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['amount'] ?></td>
            <td><?= $row['date'] ?></td>
        </tr>
        <?php } ?>
    </table>

    <!-- Complaints Report -->
    <h3>Complaints Report </h3>
    
    <table class="report-table">
        <tr>
            <th>Sr. No</th>
            <th>Student Name</th>
            <th>Complaint</th>
            <th>Status</th>
        </tr>
        <?php
        $sr_no = 1;
        while ($row = $complaintsResult->fetch_assoc()) { ?>
        <tr>
            <td><?= $sr_no++ ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['complaint'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php } ?>
    </table>
    <a href="export_fees.php" class="btn btn-success">Export</a>
    <a href="export_leave_requests.php" class="btn btn-success">Export</a>
    <a href="export_fees.php" class="btn btn-success">Export</a>
    <a href="export_complaints.php" class="btn btn-success">Export</a>
    <a href="export_all.php" class="btn btn-success">Export</a>
    <a href="export_announcement.php" class="btn btn-success">Export Announcement to Excel</a><br>
    
</body>
</html>
