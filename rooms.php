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

// Insert Room
if (isset($_POST['add_room'])) {
    $room_no = $_POST['room_no'];
    $type = $_POST['type'];

    $sql = "INSERT INTO rooms (room_no, type) VALUES ('$room_no', '$type')";
    if ($conn->query($sql)) {
        $message = 'Room added successfully!';
    } else {
        $message = 'Error: ' . $conn->error;
    }
}

// Update Room  
if (isset($_POST['update_room'])) {
    $id = $_POST['id'];
    $room_no = $_POST['room_no'];
    $type = $_POST['type'];

    $sql = "UPDATE rooms SET room_no='$room_no', type='$type' WHERE id=$id";
    if ($conn->query($sql)) {
        $message = 'Room updated successfully!';
    } else {
        $message = 'Error: ' . $conn->error;
    }
}

// Delete Room
if (isset($_POST['delete_room'])) {
    $id = $_POST['id'];

    // Remove students from the room before deleting it
    $sql = "UPDATE student_log SET room_number=NULL WHERE room_number=(SELECT room_no FROM rooms WHERE id=$id)";
    $conn->query($sql);

    // Now delete the room
    $sql = "DELETE FROM rooms WHERE id=$id";
    if ($conn->query($sql)) {
        $message = 'Room deleted successfully!';
    } else {
        $message = 'Error: ' . $conn->error;
    }
}

// Assign Room to Student with Validation
if (isset($_POST['assign_room'])) {
    $student_id = $_POST['student_id'];
    $room_number = $_POST['room_number'];

    // Check if the room already has two students assigned
    $sql_check = "SELECT COUNT(*) as room_count FROM student_log WHERE room_number='$room_number'";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();
    
    if ($row_check['room_count'] >= 2) {
        $message = 'Error: This room already has two students assigned!';
    } else {
        $sql = "UPDATE student_log SET room_number='$room_number' WHERE id=$student_id";
        if ($conn->query($sql)) {
            $message = 'Room assigned to student successfully!';
        } else {
            $message = 'Error: ' . $conn->error;
        }
    }
}

// Remove Student from Room
if (isset($_POST['remove_room'])) {
    $student_id = $_POST['student_id'];
    $sql = "UPDATE student_log SET room_number=NULL WHERE id=$student_id";
    if ($conn->query($sql)) {
        $message = 'Room removed from student successfully!';
    } else {
        $message = 'Error: ' . $conn->error;
    }
}

// Fetch Rooms
$rooms = $conn->query("SELECT * FROM rooms");

// Fetch Students
$students = $conn->query("SELECT * FROM student_log");

// Fetch Students Without Rooms
$students_no_room = $conn->query("SELECT * FROM student_log WHERE room_number IS NULL");

// Fetch Students with Rooms
$students_with_room = $conn->query("SELECT * FROM student_log WHERE room_number IS NOT NULL");

// Fetch Students with Rooms, including room type
$students_with_room = $conn->query("SELECT s.id, s.name, s.course, s.room_number, r.type FROM student_log s LEFT JOIN rooms r ON s.room_number = r.room_no WHERE s.room_number IS NOT NULL");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room & Student Management</title>
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
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background-color: #f9fafb;
        }

        h2 {
            font-size: 1.75rem;
            margin-bottom: 20px;
            color: rgb(49, 46, 129);
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

        form {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        input, select {
            padding: 10px;
            font-size: 1rem;
            margin: 10px 0;
            width: 15%;
            border-radius: 5px;
            border: 1px solid rgb(229, 231, 235);
            margin-bottom: 10px;
        }

        input[type="text"]:focus, select:focus {
            border-color: rgb(79, 70, 229);
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color:#1e2a3a;
            color: white;
        }

        
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
                <a href="fees.php " class="nav-item">Fees Management</a>
                <a href="rooms.php" class="nav-item active">Room Management</a>
                <a href="leave.php" class="nav-item">Leave</a>
                <a href="complaints.php" class="nav-item">Complaints Management</a>
                <a href="notification.php" class="nav-item">Announcment</a>
                <a href="reports.php" class="nav-item">Reports</a>
                <a href="index.php" class="nav-item">Logout</a>
            </div>
</div>

<div class="content">
    <h2>Room Management</h2>

    <button id="addRoomBtn">Add Room</button>
    <div id="roomFormPopup" style="display:none;">
        <form method="POST" id="roomForm">
            <input type="text" name="room_no" placeholder="Room No" required>
            <select name="type" required>
                <option value="AC">AC</option>
                <option value="Non-AC">Non-AC</option>
            </select>
            <button type="submit" name="add_room">Add Room</button>
        </form>
    </div>

    <h3>All Rooms</h3>
    <table>
        <tr>
            <th>Sr. No</th>
            <th>ID</th>
            <th>Room No</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        <?php
        $sr_no = 1;
        while ($row = $rooms->fetch_assoc()) { ?>
        <tr>
            <td><?= $sr_no++ ?></td>
            <td><?= $row['id'] ?></td>
            <td><?= $row['room_no'] ?></td>
            <td><?= $row['type'] ?></td>
            <td class="actions">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="room_no" value="<?= $row['room_no'] ?>" required>
                    <input type="text" name="type" value="<?= $row['type'] ?>" required>
                    <button type="submit" name="update_room">Update</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_room">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Assign Room to Student</h2>
    <form method="POST">
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php while ($row = $students_no_room->fetch_assoc()) { ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?> (<?= $row['course'] ?>)</option>
            <?php } ?>
        </select>
        <select name="room_number" required>
            <option value="">Select Room</option>
            <?php $rooms->data_seek(0); while ($row = $rooms->fetch_assoc()) { ?>
                <option value="<?= $row['room_no'] ?>"><?= $row['room_no'] ?> (<?= $row['type'] ?>)</option>
            <?php } ?>
        </select>
        <button type="submit" name="assign_room">Assign Room</button>
    </form>

    <h3>Students Assigned to Rooms</h3>
    <table>
        <tr>
            <th>Sr. No</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Course</th>
            <th>Room No</th>
            <th>Room Type</th>
            <th>Actions</th>
        </tr>
        <?php
        $sr_no = 1;
        while ($row = $students_with_room->fetch_assoc()) { ?>
        <tr>
            <td><?= $sr_no++ ?></td>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['course'] ?></td>
            <td><?= $row['room_number'] ?></td>
            <td><?= $row['type'] ?></td>
            <td class="actions">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="student_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="remove_room">Remove Room</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<script>
    document.getElementById("addRoomBtn").onclick = function() {
        document.getElementById("roomFormPopup").style.display = "block";
    };
</script>
</body>
</html>
