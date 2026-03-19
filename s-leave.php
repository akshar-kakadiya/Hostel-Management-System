<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "ms");

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $reason = trim($_POST['reason']);

    // Convert to date format
    $current_date = date("Y-m-d"); // Today's date
    $start_date_obj = date_create($start_date);
    $end_date_obj = date_create($end_date);

    // Validation: Ensure dates are valid
    if (!$start_date_obj || !$end_date_obj) {
        echo "<script>alert('Invalid date format!'); window.location='s-leave.php';</script>";
        exit();
    }

    // Validation: Start date should not be in the past
    if ($start_date < $current_date) {
        echo "<script>alert('Enter valid date!!!'); window.location='s-leave.php';</script>";
        exit();
    }

    // Validation: End date should not be before the start date
    if ($end_date < $start_date) {
        echo "<script>alert('Enter valid date!!!'); window.location='s-leave.php';</script>";
        exit();
    }

    // Prepare SQL query
    $query = "INSERT INTO leave_requests (student_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $student_id, $start_date, $end_date, $reason);
    
    if ($stmt->execute()) {
        echo "<script>alert('Leave request submitted successfully!'); window.location='s-leave.php';</script>";
    } else {
        echo "<script>alert('Error submitting leave request.'); window.location='s-leave.php';</script>";
    }
}
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

        /* main content */
        

        h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .container { 
            width: 800px;
            margin-top: 20px;
            margin-left: 400px;
            padding: 20px;
            background: white; 
            margin-bottom: 30px;
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        input[type="date"], textarea, button {
            width: 30%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 1rem;
        }

        button {
            background-color: #4f46e5;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #6259ff;
        }

        h3 {
            margin-top: 40px;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
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
                <a class="nav-item" href="s-dashboard.php">Dashboard</a>
                <a class="nav-item" href="s-detail.php">My Details</a>
                <a class="nav-item active" href="s-leave.php">Leave</a>
                <a class="nav-item" href="s-complaints.php">Complaints</a>
                <!-- <a class="nav-item" href="s-noti.php">Announcment</a> -->
                <a class="nav-item" href="s-logout.php">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <h2>Apply for Leave</h2>
                <form method="POST">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" required><br>

                    <label>End Date:</label>
                    <input type="date" name="end_date" required><br>

                    <label>Reason:</label>
                    <textarea name="reason" rows="4" required></textarea><br>

                    <button type="submit">Submit Request</button>
                </form>

                <h3>Leave History</h3>
                <table>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    $query = "SELECT * FROM leave_requests WHERE student_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $student_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['start_date']}</td>
                            <td>{$row['end_date']}</td>
                            <td>{$row['reason']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
