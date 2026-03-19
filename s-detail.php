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

// Fetch user data from the database
$student_id = $_SESSION['student_id'];
$sql = "SELECT * FROM student_log WHERE id = '$student_id'";
$result = $conn->query($sql);
$student = $result->fetch_assoc();



// Close connection after query execution
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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

        .dashboard h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: rgb(31, 41, 55);
            margin-bottom: 1.5rem;
            margin-top: 20px;
            padding-left: 20%;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .info-item {
            background-color: rgb(243, 244, 246); /* Light gray for better contrast */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .info-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .info-item h3 {
            font-size: 1.15rem;
            color: rgb(37, 99, 235); /* Bright blue for headers */
            border-bottom: 1px dotted rgb(0, 0, 0);
            margin-bottom: 8px;
        }

        .info-item p {
            font-size: 1rem;
            color: rgb(75, 85, 99); /* Subtle gray for body text */
        }   

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
            <div class="sidebar-header">
                <h1>Student Portal</h1>
            </div>
            <div class="sidebar-nav">
                <a class="nav-item" href="s-dashboard.php"> Dashboard</a>
                <a class="nav-item active" href="#"> My Details</a>
                <a class="nav-item" href="s-leave.php">leave</a>
                <a class="nav-item" href="s-complaints.php"> Complaints</a>
                <!-- <a class="nav-item" href="s-noti.php">Announcment</a> -->
                <a class="nav-item" href="s-login.php"> Logout</a>
            </div>
        </div>

        <div class="dashboard">
                <h2>My Details</h2>
            </div>

    <!-- Profile Container -->
    <div class="container">

        <div class="profile-info">
            
            <!-- Profile Info -->
            <?php
            $info_items = [
                'Name' => 'name',
                'Mobile Number' => 'user_mobile',
                'Email' => 'email',
                'Guardian Name' => 'guardian_name',
                'Guardian Mobile No.' => 'guardian_mobile',
                'Birth Date' => 'birthday',
                'Age' => 'age',
                'Address' => 'address',
                'Course' => 'course',
                'College Name' => 'college_name',
                'Semester' => 'college_year',
                'Joining Date' => 'starting_date',
                'Room Number' => 'room_number'
            ];

            foreach ($info_items as $label => $field) {
                echo "<div class='info-item'><h3>{$label}:</h3><p>{$student[$field]}</p></div>";
            }

            
            ?>
        </div>
    </div>

    

</body>
</html>
