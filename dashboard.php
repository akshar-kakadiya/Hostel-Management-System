<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ms');

// Fetch Total Students
$totalStudentsQuery = "SELECT COUNT(*) AS total_students FROM student_log";
$totalStudentsResult = $conn->query($totalStudentsQuery);
$totalStudents = $totalStudentsResult->fetch_assoc()['total_students'];

// Fetch Total Fees Collection
$totalFeesQuery = "SELECT SUM(amount) AS total_fees FROM fees";
$totalFeesResult = $conn->query($totalFeesQuery);
$totalFees = $totalFeesResult->fetch_assoc()['total_fees'];

// Fetch Total Rooms and Occupied Rooms
$totalRoomsQuery = "SELECT COUNT(*) AS total_rooms FROM rooms";
$totalRoomsResult = $conn->query($totalRoomsQuery);
$totalRooms = $totalRoomsResult->fetch_assoc()['total_rooms'];

// Fetch total complaints count
$query = "SELECT COUNT(*) AS total_complaints FROM complaints";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_complaints = $row['total_complaints'];

// Fetch total students on leave (approved leave)
$query = "SELECT COUNT(*) AS total_on_leave FROM leave_requests WHERE status = 'approved'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_on_leave = $row['total_on_leave'];

// $occupiedRoomsQuery = "SELECT COUNT(*) AS occupied_rooms FROM rooms WHERE status = 'occupied'";
// $occupiedRoomsResult = $conn->query($occupiedRoomsQuery);
// $occupiedRooms = $occupiedRoomsResult->fetch_assoc()['occupied_rooms'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HMS Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
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
        .main-content { flex: 1; padding: 2rem; margin-left: 256px; }
        .dashboard h2 { font-size: 1.75rem; font-weight: 700; color: rgb(31, 41, 55); margin-bottom: 1.5rem; background: linear-gradient(135deg, rgb(79, 70, 229), rgb(98, 89, 255)); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background-color: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); display: flex; align-items: center; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .stat-card img { width: 40px; height: 40px; margin-right: 1rem; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); }
    </style>
</head>
<body>
    <div class="app">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header"><h1>Hostel Management</h1></div>
            <div class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">Dashboard</a>
                <a href="student.php" class="nav-item">Students Management</a>
                <a href="fees.php " class="nav-item">Fees Management</a>
                <a href="rooms.php" class="nav-item">Room Management</a>
                <a href="leave.php" class="nav-item">Leave</a>
                <a href="complaints.php" class="nav-item">Complaints Management</a>
                <a href="notification.php" class="nav-item">Announcment</a>
                <a href="report.php" class="nav-item">Reports</a>
                <a href="index.php" class="nav-item">Logout</a>
            </div>
        </div>
        <div class="main-content">
            <div class="dashboard"><h2>Admin Dashboard</h2></div>

            <div class="stats-grid">
                <!-- Total Students -->
                <div class="stat-card">
                    <img src="img/user.png" alt="total students">
                    <div class="stat-info">
                        <div class="stat-label">Total Students</div>
                        <div class="stat-value"><?php echo $totalStudents; ?></div>
                    </div>
                </div>
                
                <!-- Total Fees Collection -->
                <div class="stat-card">
                    <img src="img/fees.svg" alt="total fees">
                    <div class="stat-info">
                        <div class="stat-label">Total Fees Collection</div>
                        <div class="stat-value">₹<?php echo number_format($totalFees, 2); ?></div>
                    </div>
                </div>

                <!-- Total Rooms -->
                <div class="stat-card">
                    <img src="img/room.svg" alt="total rooms">
                    <div class="stat-info">
                        <div class="stat-label">Total Rooms</div>
                        <div class="stat-value"><?php echo $totalRooms; ?></div>
                    </div>
                </div>

                <!-- Total Complaints -->
                <div class="stat-card">
                    <img src="img/complaints.png" alt="Total Complaints">
                    <div class="stat-info">
                        <div class="stat-label">Total Complaints</div>
                        <div class="stat-value"><p><?php echo $total_complaints; ?></p></div>
                    </div>
                </div>

                <!-- Leave student -->
                <div class="stat-card">
                    <img src="img/complaints.png" alt="Student on leave">
                    <div class="stat-info">
                        <div class="stat-label">Student on leave</div>
                        <div class="stat-value"><p><?php echo $total_on_leave; ?></p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
