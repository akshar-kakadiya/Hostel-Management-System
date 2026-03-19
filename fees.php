<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch students for dropdown
$sql_students = "SELECT * FROM student_log WHERE id NOT IN (SELECT student_id FROM fees)";
$result_students = $conn->query($sql_students);

// Fetch fees records
$sql_fees = "SELECT fees.id, student_log.name, fees.amount, fees.date FROM fees JOIN student_log ON fees.student_id = student_log.id";
$result_fees = $conn->query($sql_fees);

// Handle AJAX request for student details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id']) && !isset($_POST['submit'])) {
    $student_id = intval($_POST['student_id']);
    $sql = "SELECT * FROM student_log WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $student_id = $_POST['student_id'];
    $fees_amount = $_POST['fees_amount'];
    $date = $_POST['date'];

    if (!empty($student_id) && !empty($fees_amount) && !empty($date)) {
        // Insert fees record into 'fees' table
        $sql_insert = "INSERT INTO fees (student_id, amount, date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iis", $student_id, $fees_amount, $date);

        if ($stmt->execute()) {
            // Remove payment status update
            echo "<script>alert('Fees added successfully!'); window.location.href='fees.php';</script>";
        } else {
            echo "<script>alert('Error adding fees.');</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields.');</script>";
    }
}


// Handle Edit Fee Record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_submit'])) {
    $fee_id = $_POST['fee_id'];
    $fees_amount = $_POST['fees_amount'];
    $date = $_POST['date'];

    if (!empty($fee_id) && !empty($fees_amount) && !empty($date)) {
        $sql_update = "UPDATE fees SET amount = ?, date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssi", $fees_amount, $date, $fee_id);

        if ($stmt->execute()) {
            echo "<script>alert('Fees updated successfully!'); window.location.href='fees.php';</script>";
        } else {
            echo "<script>alert('Error updating fees.');</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields.');</script>";
    }
}

// Handle Delete Fee Record
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql_delete = "DELETE FROM fees WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Fees record deleted successfully!'); window.location.href='fees.php';</script>";
    } else {
        echo "<script>alert('Error deleting fees record.');</script>";
    }
}

// Fetch fee details for editing
$edit_fee = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $sql_edit = "SELECT * FROM fees WHERE id = ?";
    $stmt = $conn->prepare($sql_edit);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_fee = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Fees Management</title>
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
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            margin: 50px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin: 10px 0;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background: #f2f2f2;
        }
        #student-details {
            display: none;
            border: 1px solid #ccc;
            padding: 10px;
            background: #f2f2f2;
            margin-top: 10px;
            text-align: left;
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
                <a href="fees.php " class="nav-item active">Fees Management</a>
                <a href="rooms.php" class="nav-item">Room Management</a>
                <a href="leave.php" class="nav-item">Leave</a>
                <a href="complaints.php" class="nav-item">Complaints Management</a>
                <a href="notification.php" class="nav-item">Announcment</a>
                <a href="reports.php" class="nav-item">Reports</a>
                <a href="index.php" class="nav-item">Logout</a>
            </div>
</div>

<div class="container">
    <h1>Manage Fees</h1>

    <form action="fees.php" method="post">
        <div class="form-group">
            <label>Select Student:</label>
            <select name="student_id" id="student_id" required>
                <option value="">Select a student</option>
                <?php while ($row = $result_students->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['name'] . ' - Room ' . $row['room_number']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="button" id="showDetails">Show Details</button>

        <div id="student-details">
            <h3>Student Details</h3>
            <table>
                <tr><th>Name</th><td id="student_name"></td></tr>
                <tr><th>Email</th><td id="student_email"></td></tr>
                <tr><th>Guardian Name</th><td id="guardian_name"></td></tr>
                <tr><th>Guardian Mobile</th><td id="guardian_mobile"></td></tr>
                <tr><th>Student Mobile</th><td id="user_mobile"></td></tr>
                <tr><th>Course</th><td id="student_course"></td></tr>
                <tr><th>College Year</th><td id="college_year"></td></tr>
                <tr><th>College Name</th><td id="college_name"></td></tr>
                <tr><th>Birthday</th><td id="birthday"></td></tr>
                <tr><th>Age</th><td id="age"></td></tr>
                <tr><th>Address</th><td id="address"></td></tr>
                <tr><th>Starting Date</th><td id="starting_date"></td></tr>
                <tr><th>Status</th><td id="status"></td></tr>
                <tr><th>Room Number</th><td id="room_number"></td></tr>
            </table>
        </div>

        <div class="form-group">
            <label>Fees Amount:</label>
            <input type="number" name="fees_amount" required>
        </div>
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <button type="submit" name="submit">Add Fees</button>
    </form>

    <h2>Fees Records</h2>
    <table>
        <thead>
            <tr>
                <th>SR No.</th>
                <th>Student Name</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $sr_no = 1;
            $result_fees = $conn->query($sql_fees); // Re-fetch fees records
            while ($row = $result_fees->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $sr_no++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                        <!-- <a href="fees.php?edit_id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a> -->
                        <a href="fees.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('showDetails').addEventListener('click', function() {
        var student_id = document.getElementById('student_id').value;
        if (student_id) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'fees.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                var result = JSON.parse(xhr.responseText);
                if (result.status === "error") {
                    alert('No details found');
                } else {
                    document.getElementById('student_name').innerText = result.name;
                    document.getElementById('student_email').innerText = result.email;
                    document.getElementById('guardian_name').innerText = result.guardian_name;
                    document.getElementById('guardian_mobile').innerText = result.guardian_mobile;
                    document.getElementById('user_mobile').innerText = result.user_mobile;
                    document.getElementById('student_course').innerText = result.course;
                    document.getElementById('college_year').innerText = result.college_year;
                    document.getElementById('college_name').innerText = result.college_name;
                    document.getElementById('birthday').innerText = result.birthday;
                    document.getElementById('age').innerText = result.age;
                    document.getElementById('address').innerText = result.address;
                    document.getElementById('starting_date').innerText = result.starting_date;
                    document.getElementById('status').innerText = result.status;
                    document.getElementById('room_number').innerText = result.room_number;
                    document.getElementById('student-details').style.display = 'block';
                }
            };
            xhr.send('student_id=' + student_id);
        } else {
            alert('Please select a student');
        }
    });
</script>

</body>
</html>
