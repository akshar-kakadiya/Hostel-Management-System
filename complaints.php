<?php
$conn = mysqli_connect("localhost", "root", "", "ms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8"); // Ensuring UTF-8 compatibility

// Approve Complaint
if (isset($_GET['approve'])) {
    $id = mysqli_real_escape_string($conn, $_GET['approve']);
    $query = "UPDATE complaints SET status='Approved' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Complaint Approved!'); window.location.href='complaints.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Reject Complaint
if (isset($_GET['reject'])) {
    $id = mysqli_real_escape_string($conn, $_GET['reject']);
    $query = "UPDATE complaints SET status='Rejected' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Complaint Rejected!'); window.location.href='complaints.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete Complaint
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM complaints WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Complaint Deleted!'); window.location.href='complaints.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch Complaints
$result = mysqli_query($conn, "SELECT * FROM complaints ORDER BY IFNULL(created_at, NOW()) DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Complaints</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #111827;
            line-height: 1.5;
        }
        .app {
            min-height: 100vh;
            display: flex;
        }
        .sidebar {
            width: 256px;
            background-color: white;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }
        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #4f46e5, #6259ff);
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
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .nav-item:hover {
            background: linear-gradient(135deg, #4f46e5, #6259ff);
            color: white;
            transform: translateX(5px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, #4f46e5, #6259ff);
            color: white;
        }
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .container { 
            width: 800px;
            margin-top: 30px;
            margin-left: 400px;
            padding: 20px;
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }
        h2 { 
            margin-top: 20px;
            color: #4f46e5; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center; 
        }
        th { 
            background-color:#1e2a3a;
            color: white; 
        }
        .btn { 
            padding: 5px 10px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        .approve { background: #10b981; color: white; }
        .reject { background: #ef4444; color: white; }
        .delete { background: #6b7280; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h1>Hostel Management</h1>
    </div>
    <div class="sidebar-nav">
        <a href="dashboard.php" class="nav-item">Dashboard</a>
        <a href="student.php" class="nav-item">Students Management</a>
        <a href="fees.php" class="nav-item">Fees Management</a>
        <a href="rooms.php" class="nav-item">Room Management</a>
        <a href="leave.php" class="nav-item">Leave</a>
        <a href="complaints.php" class="nav-item active">Complaints Management</a>
        <!-- <a href="notification.php" class="nav-item">Announcement</a> -->
        <a href="reports.php" class="nav-item">Reports</a>
        <a href="index.php" class="nav-item">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Student Complaints</h2>
    <table>
        <tr>
            <th>SR No.</th>
            <th>Student Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php 
        $sr_no = 1; 
        while ($row = mysqli_fetch_assoc($result)) { 
            if (empty($row['status'])) { 
                $row['status'] = "Pending"; 
            }
        ?>
        <tr>
            <td><?php echo $sr_no++; ?></td>
            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
            <td><?php echo htmlspecialchars($row['complaint']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>
            <td>
                <?php if ($row['status'] == 'Pending') { ?>
                    <a href="?approve=<?php echo $row['id']; ?>" class="btn approve">Approve</a>
                    <a href="?reject=<?php echo $row['id']; ?>" class="btn reject">Reject</a>
                <?php } ?>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn delete">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>


<!-- CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    complaint TEXT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    created_at datetime DEFAULT CURRENT_TIMESTAMP
); -->