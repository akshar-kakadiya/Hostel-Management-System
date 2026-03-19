<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "ms");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Approve Leave
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $query = "UPDATE leave_requests SET status='Approved' WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();   
}

// Reject Leave
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $query = "UPDATE leave_requests SET status='Rejected' WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Remove Leave
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $query = "DELETE FROM leave_requests WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Get pending leave requests
$query_pending = "SELECT leave_requests.*, student_log.name, student_log.room_number 
                  FROM leave_requests 
                  JOIN student_log ON leave_requests.student_id = student_log.id 
                  WHERE leave_requests.status = 'pending'";
$result_pending = $conn->query($query_pending);

// Get approved & rejected leave requests
$query_approved = "SELECT leave_requests.*, student_log.name, student_log.room_number 
                   FROM leave_requests 
                   JOIN student_log ON leave_requests.student_id = student_log.id 
                   WHERE leave_requests.status = 'approved' OR leave_requests.status = 'rejected'";
$result_approved = $conn->query($query_approved);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management - HMS Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: rgb(249, 250, 251);
            color: rgb(17, 24, 39);
            line-height: 1.5;
            display: flex;
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
            display: block;
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

        .content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }

        h2 {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid rgb(229, 231, 235);
            text-align: left;
        }

        th {
            background-color:#1e2a3a;
            color: white;
        }

        tr:hover {
            background-color: rgb(243, 244, 246);
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-approve {
            background-color: green;
        }

        .btn-reject {
            background-color: red;
        }

        .btn-remove {
            background-color: darkgray;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h1>Hostel Management</h1></div>
        <div class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">Dashboard</a>
                <a href="student.php" class="nav-item">Students Management</a>
                <a href="fees.php " class="nav-item">Fees Management</a>
                <a href="rooms.php" class="nav-item">Room Management</a>
                <a href="leave.php" class="nav-item active">Leave</a>
                <a href="complaints.php" class="nav-item">Complaints Management</a>
                <a href="notification.php" class="nav-item">Announcment</a>
                <a href="reports.php" class="nav-item">Reports</a>
                <a href="index.php" class="nav-item">Logout</a>
            </div>
    </div>

    <div class="content">
        <h2>Pending Leave Requests</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result_pending->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td>
                        <a href="?approve=<?= $row['id']; ?>" class="btn btn-approve">Approve</a>
                        <a href="?reject=<?= $row['id']; ?>" class="btn btn-reject">Reject</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <h2>Processed Leave Requests</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result_approved->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <a href="?remove=<?php echo $row['id']; ?>" class="btn btn-remove">Remove</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>