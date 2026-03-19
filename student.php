<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ms');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data for form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM student_log WHERE id = '$id'");
    $student = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['guardian_name'])) {
    $id = $_POST['id'] ?? null;
    $name = $conn->real_escape_string($_POST['name']);
    $guardian_name = $conn->real_escape_string($_POST['guardian_name']);
    $guardian_mobile = $conn->real_escape_string($_POST['guardian_mobile']);
    $user_mobile = $conn->real_escape_string($_POST['user_mobile']);
    $email = $conn->real_escape_string($_POST['email']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $age = $conn->real_escape_string($_POST['age']);
    $address = $conn->real_escape_string($_POST['address']);
    $course = $conn->real_escape_string($_POST['course']);
    $college = $conn->real_escape_string($_POST['college']);
    $college_year = $conn->real_escape_string($_POST['college_year']);
    $starting_date = $conn->real_escape_string($_POST['starting_date']);

    if ($id) {
        // Update existing student data
        $sql = "UPDATE student_log SET 
                    name = '$name',
                    guardian_name = '$guardian_name',
                    guardian_mobile = '$guardian_mobile',
                    user_mobile = '$user_mobile',
                    email = '$email',
                    birthday = '$birthday',
                    age = '$age',
                    address = '$address',
                    course = '$course',
                    college_name = '$college',
                    college_year = '$college_year',
                    starting_date = '$starting_date',
                    status = 1 -- Mark as filled admission form
                WHERE id = '$id'";
    } else {
        // Insert new student data with status 0 (not filled out form)
        $sql = "INSERT INTO student_log (name, guardian_name, guardian_mobile, user_mobile, email, birthday, age, address, course, college_name, college_year, starting_date, status) 
        VALUES ('$name', '$guardian_name', '$guardian_mobile', '$user_mobile', '$email', '$birthday', '$age', '$address', '$course', '$college', '$college_year', '$starting_date', 0)";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Student data saved successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Handle deletion of student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_id'])) {
    $remove_id = $conn->real_escape_string($_POST['remove_id']);

    // Delete the student record from the database
    $deleteQuery = "DELETE FROM student_log WHERE id = '$remove_id'";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "<script>alert('Student removed successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    // Refresh the page to reflect changes
    echo "<script>window.location.href = window.location.href;</script>";
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : '';  // Retrieve ID from the URL

$starting_date_query = "SELECT starting_date FROM student_log WHERE id = '$id'";
$result = $conn->query($starting_date_query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $starting_date = $row['starting_date'];
    // Now you can use $starting_date as needed
}

// Query for registered students (status 0)
$registeredStudentsQuery = "SELECT * FROM student_log WHERE status = 0";
$registeredStudentsResult = $conn->query($registeredStudentsQuery);

// Query for all students (status 1)
$allStudentsQuery = "SELECT * FROM student_log WHERE status = 1";
$allStudentsResult = $conn->query($allStudentsQuery);

// Get sort column and order from GET
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'id'; // Default to 'id'
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'ASC'; // Default to 'ASC'

// Validate column for sorting
$validColumns = ['id', 'name', 'email', 'mobile', 'college', 'starting_date'];
if (!in_array($sortColumn, $validColumns)) {
    $sortColumn = 'id'; // Default to 'id' if invalid column
}

// Validate sorting order
if ($sortOrder !== 'ASC' && $sortOrder !== 'DESC') {
    $sortOrder = 'ASC'; // Default to 'ASC' if invalid order
}

// Modify query to order by the chosen column and order
$allStudentsQuery = "SELECT * FROM student_log WHERE status = 1 ORDER BY $sortColumn $sortOrder";
$allStudentsResult = $conn->query($allStudentsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Student Management</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #1e2a3a;
            color: white;
        }

        /* Link Styling for Sorting */
        th a {
            color: white; 
            text-decoration: none; 
            display: block; 
        }

        table tr:hover {
            background-color: #f4f4f9;
        }

        table td form button {
            padding: 8px 12px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table td form button:hover {
            background-color: #cc0000;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-30%, -50%);
            width: 90%;
            max-width: 600px;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            
        }

        .modal-content {
            background-color: rgb(162, 164, 168);
            padding: 20px;
            border-radius: 5px;
            width: 100%; 
            max-width: 800px; 
            box-sizing: border-box;
            max-height: 80vh; 
            overflow-y: auto; 
        }


        .modal-content form {
            display: flex;
            flex-direction: column;
        }

        .modal-content input,
        .modal-content select {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .modal-content button {
            padding: 10px;
            width: 30%;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #ff4d4d;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
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
                <a href="student.php" class="nav-item active">Students Management</a>
                <a href="fees.php " class="nav-item">Fees Management</a>
                <a href="rooms.php" class="nav-item">Room Management</a>
                <a href="leave.php" class="nav-item">Leave</a>
                <a href="complaints.php" class="nav-item">Complaints Management</a>
                <a href="notification.php" class="nav-item">Announcment</a>
                <a href="reports.php" class="nav-item">Reports</a>
                <a href="index.php" class="nav-item">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
    <div class="main-content">
            <div class="dashboard">
                <h2>Student Management</h2>
            </div>

        <div class="content">
            <h3>Registered Students</h3>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($registeredStudentsResult->num_rows > 0) {
                    while ($row = $registeredStudentsResult->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                <a href='#' onclick='openForm({$row['id']})'>Fill Admission Form</a> |
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='remove_id' value='{$row['id']}'>
                                    <button type='submit' onclick=\"return confirm('Are you sure you want to remove this student?')\">Remove</button>
                                    
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No students registered yet.</td></tr>";
                }
                ?>
            </tbody>
            </table>
            <br>
                <h3>All Students (Filled Admission Form)</h3>
        <table>
            <thead>
                <tr>
                    <th>SR No.</th>
                    <th><a href="?sortColumn=name&sortOrder=<?php echo ($sortColumn == 'name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Name</a></th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>College</th>
                    <th><a href="?sortColumn=starting_date&sortOrder=<?php echo ($sortColumn == 'starting_date' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Joining Date</a></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $srNo = 1;
                while ($row = $allStudentsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$srNo}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['user_mobile']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['college_name']}</td>
                            <td>{$row['starting_date']}</td>
                            <td>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='remove_id' value='{$row['id']}'>
                                            <button type='submit' onclick=\"return confirm('Are you sure you want to remove this student?')\">Remove</button>
                                        </form>
                                    </td>
                        </tr>";
                    $srNo++;
                }
                ?>
            </tbody>
        </table>
        
    </div>

    <!-- The Modal -->
    <div id="admissionModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeForm()">&times;</span>
            <h2>Admission Form</h2>
            <form method="POST">
                <input type="hidden" name="id" id="studentId" value="">
                <label>Name:</label><br>
                <input type="text" name="name" required><br>
                <label>Guardian's Name:</label><br>
                <input type="text" name="guardian_name" required><br>
                <label>Guardian's Mobile:</label><br>
                <input type="text" name="guardian_mobile" maxlength="10" required><br>
                <label>User's Mobile:</label><br>
                <input type="text" name="user_mobile" maxlength="10" required><br>
                <label>Email:</label><br>
                <input type="email" name="email" required><br>
                <label>Birthday:</label><br>
                <input type="date" id="birthday" name="birthday" onchange="calculateAge()" required>
                <label>Age:</label><br>
                <input type="number" id="age" name="age" placeholder="Age" readonly>
                <label>Address:</label><br>
                <input type="text" name="address" required><br>
                <label>Course:</label><br>
                <input type="text" name="course" required><br>
                <label>College Name:</label><br>
                <input type="text" name="college" required><br>
                <label>College Year:</label><br>
                <input type="text" name="college_year" required><br>
                <label>Starting Date:</label><br>
                <input type="date" name="starting_date" required><br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
    <script>
        // Function to open the admission form
        function openForm(id) {
            document.getElementById("studentId").value = id;
            document.getElementById("admissionModal").style.display = "block";
        }

        // Function to close the modal
        function closeForm() {
            document.getElementById("admissionModal").style.display = "none";
        }

        // Close the modal if clicked outside
        window.onclick = function(event) {
            const modal = document.getElementById("admissionModal");
            if (event.target === modal) {
                closeForm();
            }
        }

        function calculateAge() {
        const birthdayInput = document.getElementById('birthday');
        const ageInput = document.getElementById('age');

        // Get the birthdate value
        const birthdate = new Date(birthdayInput.value);
        if (isNaN(birthdate.getTime())) return; // Invalid date check

        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();

        // Adjust age if the birthday hasn't occurred yet this year
        const hasBirthdayOccurred = 
            today.getMonth() > birthdate.getMonth() || 
            (today.getMonth() === birthdate.getMonth() && today.getDate() >= birthdate.getDate());
        if (!hasBirthdayOccurred) {
            age -= 1;
        }

        // Set the calculated age
        ageInput.value = age;
    }
    </script>
</body>
</html>