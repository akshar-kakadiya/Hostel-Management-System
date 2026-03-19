<?php
$conn = new mysqli('localhost', 'root', '', 'ms');

session_start();
$student_id = $_SESSION['student_id']; // Logged-in student ID

$sql = "SELECT * FROM notifications 
        WHERE student_id = '$student_id' OR student_id IS NULL
        ORDER BY date_time DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Notifications</title>
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
            justify-content: flex-start; /* Align items to the start */
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

        /* Content */
        .container {
            max-width: 1000px;
            width: calc(70% - 256px); /* Full width minus sidebar width */
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #4c6a92;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4c6a92;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        .no-notifications {
            text-align: center;
            font-size: 18px;
            color: #777;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 100%; /* Full width on smaller screens */
                margin-left: 0; /* Remove left margin */
                padding: 15px; /* Adjust padding for smaller screens */
            }

            .sidebar {
                width: 80px; /* Reduce sidebar width on small screens */
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
            <a class="nav-item" href="s-dashboard.php">Dashboard</a>
            <a class="nav-item" href="s-detail.php">My Details</a>
            <a class="nav-item" href="s-leave.php">Leave</a>
            <a class="nav-item" href="s-complaints.php">Complaints</a>
            <a class="nav-item active" href="s-noti.php">Announcement</a>
            <a class="nav-item" href="s-logout.php">Logout</a>
        </div>
    </div>

    <!-- Notifications Content -->
    <div class="container">
        <h2>Student Notice</h2>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Message</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p class="no-notifications">No Announcements available.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
