<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "ms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE admin_id='$admin_id' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login success
        $_SESSION['admin_id'] = $admin_id;
        header("Location: dashboard.php"); 
        exit();
    } else { 
        // Login failed
        $_SESSION['error'] = "Invalid Admin ID or Password";
        header("Location: index.php"); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - HMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: rgb(249, 250, 251);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: white;
            width: 400px;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-container h1 {
            color: rgb(79, 70, 229);
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgb(229, 231, 235);
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: rgb(79, 70, 229);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .login-btn {
            background-color: rgb(79, 70, 229);
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: rgb(67, 56, 202);
        }

        .forgot-password {
            font-size: 0.875rem;
            color: rgb(107, 114, 128);
            text-decoration: none;
        }

        .forgot-password:hover {
            color: rgb(79, 70, 229);
        }

        .error-message {
            color: red;
            font-size: 0.875rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <form method="POST">
            <input type="text" name="username" class="form-input" placeholder="Username" required>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
            <a href="s-login.php">Are you a student?</a>
        </form>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // Clear the error message after displaying it
        }
        ?>
    </div>
</body>
</html>
