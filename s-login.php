<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "ms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM student_log WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_name'] = $row['name'];
        echo "<script>alert('Login Successful!'); window.location.href='s-dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid Email or Password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { 
            background-color: #f9fafb; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            padding: 20px;
        }
        .login-container { 
            background: white; 
            padding: 2rem; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            text-align: center; 
            width: 100%; 
            max-width: 400px; 
        }
        .login-container h1 { 
            color: #4f46e5; 
            margin-bottom: 1rem; 
            font-size: 1.8rem; 
        }
        .form-input { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 1rem; 
        }
        .login-btn { 
            background: #4f46e5; 
            color: white; 
            padding: 12px; 
            width: 100%; 
            border: none; 
            border-radius: 5px; 
            font-size: 1rem; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        .login-btn:hover { background: #4338ca; }
        .register-link { 
            display: block; 
            margin-top: 12px; 
            font-size: 1rem; 
            color: #6b7280; 
        }
        .register-link:hover { color: #4f46e5; text-decoration: underline; }

        @media (max-width: 480px) {
            .login-container { padding: 1.5rem; width: 90%; }
            .form-input, .login-btn { font-size: 1rem; padding: 14px; }
            .login-container h1 { font-size: 1.6rem; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Student Login</h1>
        <form method="POST">
            <input type="email" name="email" class="form-input" placeholder="Email" required>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
            <a href="s-registration.php" class="register-link">Don't have an account? Register</a>
        </form>
    </div>
</body>
</html>
