<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "ms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify user session
if (!isset($_SESSION['student_id'])) {
    header("Location: s-login.php");
    exit();
}

// Fetch logged-in student details
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM student_log WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit();
}

// Fetch total students
$sql_students = "SELECT COUNT(*) AS total_students FROM student_log";
$result_students = $conn->query($sql_students);
$row_students = $result_students->fetch_assoc();
$total_students = $row_students['total_students'];

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student</title>
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

        .app {
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
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
            background: linear-gradient(135deg, rgb(79, 70, 229), rgb(98, 89, 255));
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

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 1.5rem 2rem;
            margin-left: 256px;
                
        }

        .dashboard h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: rgb(31, 41, 55);
            margin-bottom: 1.5rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card img {
            width: 40px;
            height: 40px;
            margin-right: 1rem;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard h3 {
            animation: fadeIn 1s ease-in-out;
        }

        .stat-card {
            animation: fadeIn 0.5s ease-in-out;
        }

        .stat-card:nth-child(1) { animation-delay: 0.2s; }
        .stat-card:nth-child(2) { animation-delay: 0.4s; }
        .stat-card:nth-child(3) { animation-delay: 0.6s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                margin-bottom: 1rem;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="app">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Student Portal</h1>
            </div>
            <div class="sidebar-nav">
                <a class="nav-item active" href="s-dashboard.php">Dashboard</a>
                <a class="nav-item" href="s-detail.php">My Details</a>
                <a class="nav-item" href="s-leave.php">leave</a>
                <a class="nav-item" href="s-complaints.php">Complaints</a>
                <!-- <a class="nav-item" href="s-noti.php">Announcment</a> -->
                <a class="nav-item" href="s-logout.php">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="dashboard">
                <h2>Dashboard</h2><br>
                <h3>Welcome, <?= htmlspecialchars($student['name']) ?>!</h3>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><img src="img/user.svg" alt="total students"></div>
                    <div class="stat-info">
                        <div class="stat-label">Total Students</div>
                        <div class="stat-value"><p><?= $total_students ?></p></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><img src="img/fees.svg" alt="total fees"></div>
                    <div class="stat-info">
                        <div class="stat-label">Email</div>
                        <div><p><?= htmlspecialchars($student['email']) ?></p></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon yellow"><img src="img/room.svg" alt="rooms occupied"></div>
                    <div class="stat-info">
                        <div class="stat-label">Rooms no</div>
                        <div class="stat-value"><p><?= htmlspecialchars($student['age']) ?></p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>