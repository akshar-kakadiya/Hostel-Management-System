<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$student_id = $_SESSION['student_id'] ?? null;
$student_name = $_SESSION['student_name'] ?? null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complaint'])) {
    $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    $query = "INSERT INTO complaints (student_id, student_name, complaint) VALUES (?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iss', $student_id, $student_name, $complaint);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Complaint Submitted Successfully!'); window.location.href='s-complaints.php';</script>";
    } else {
        echo "<script>alert('Error submitting complaint!');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Handle complaint removal
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    $query = "DELETE FROM complaints WHERE id=? AND status='Pending' AND student_id=?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $id, $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: s-complaints.php");
    exit();
}

// Fetch complaints
$query = "SELECT * FROM complaints WHERE student_id=? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Complaints</title>
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
        .container {
            margin-left: 270px;
            padding: 20px;
            width: 70%;
            margin-left: 300px;
            margin-top: 30px;
            margin-bottom: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        textarea {
            width: 50%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            resize: vertical;
        }

        button {
            width: 20%;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            background: #4f46e5;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #4338ca;
        }

        /* Complaints Table */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4f46e5;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .btn {
            padding: 6px 12px;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn.remove {
            background-color: #ef4444;
        }

        .btn:hover {
            opacity: 0.8;
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
            <a class="nav-item" href="s-leave.php">Leave</a>
            <a class="nav-item active" href="s-complaints.php">Complaints</a>
            <!-- <a class="nav-item" href="s-noti.php">Announcment</a> -->
            <a class="nav-item" href="s-logout.php">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>Submit a Complaint</h1>
        <form method="POST">
            <textarea name="complaint" placeholder="Write your complaint here..." required></textarea><br>
            <button type="submit">Submit</button>
        </form>
        <br><br><br>
        <h2>Your Complaints</h2>
        <table>
            <tr>
                <th>Description</th>
                <th>Status</th>
                <th>Date & Time</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['complaint']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>
                <td>
                    <?php if ($row['status'] == 'Pending') { ?>
                        <a href="?remove=<?php echo $row['id']; ?>" class="btn remove" onclick="return confirm('Are you sure?')">Remove</a>
                    <?php } else { echo "--"; } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>