<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ms");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch students for selection (admin panel)
function getStudents($conn) {
    $sql = "SELECT id, name FROM student_log";
    return $conn->query($sql);
}

// Insert notification (admin panel)
if (isset($_POST['announce'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $message = $conn->real_escape_string($_POST['message']);
    $students = $_POST['students'];

    if (in_array("all", $students)) {
        $sql = "INSERT INTO notifications (title, message, student_id, date_time) 
                VALUES ('$title', '$message', NULL, NOW())";
        $conn->query($sql);
    } else {
        foreach ($students as $student_id) {
            $sql = "INSERT INTO notifications (title, message, student_id, date_time) 
                    VALUES ('$title', '$message', '$student_id', NOW())";
            $conn->query($sql);
        }
    }
    echo "<script>alert('Announcement sent!');</script>";
}

// Delete notification
if (isset($_POST['delete_announcement'])) {
    $id = $_POST['announcement_id'];
    $conn->query("DELETE FROM notifications WHERE id = '$id'");
    echo "<script>alert('Announcement deleted!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Announcements</title>
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
        .content {
            margin: 30px auto;
            padding: 20px;
            max-width: 750px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            background-color: #fafafa;
        }
        select {
            padding: 12px;
            height: 100px;
            width: 250px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            background-color: #fafafa;
        }
        textarea {
            resize: vertical;
        }
        button {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #3730a3;
        }
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }
        th {
            background-color: #4f46e5;
            color: white;
        }
        td button {
            background-color: red;
            color: white;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        td button:hover {
            background-color: #e11d48;
        }
        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .container {
                padding: 15px;
            }
            button, input, textarea, select {
                font-size: 14px;
            }
            th, td {
                font-size: 14px;
            }
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
            <a href="notification.php" class="nav-item active">Announcement</a>
            <a href="report.php" class="nav-item">Reports</a>
            <a href="index.php" class="nav-item">Logout</a>
        </div>
    </div>

    <div class="content">
        <!-- Create Announcement Form -->
        <div class="container">
            <h2>Create Announcement</h2>
            <form method="post">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="message" placeholder="Message" required></textarea>
                <select name="students[]" multiple>
                    <option value="all">All Students</option>
                    <?php $students = getStudents($conn); while ($row = $students->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    } ?>
                </select>
                <button type="submit" name="announce">Send Announcement</button>
            </form>
        </div>

        <!-- Announcement History -->
        <div class="container">
            <h2>Announcement History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $history = $conn->query("SELECT n.*, s.name FROM notifications n LEFT JOIN student_log s ON n.student_id = s.id ORDER BY date_time DESC");
                    while ($row = $history->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['title']}</td>
                            <td>{$row['message']}</td>
                            <td>" . ($row['name'] ?? 'All') . "</td>
                            <td>{$row['date_time']}</td>
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='announcement_id' value='{$row['id']}'>
                                    <button type='submit' name='delete_announcement'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
