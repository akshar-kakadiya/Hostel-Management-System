    <?php


    $conn = mysqli_connect("localhost", "root", "", "ms");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $query = "INSERT INTO student_log (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registration successful!'); window.location.href='s-login.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
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
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Student Registration</h1>
        <form method="POST">
            <input type="text" name="name" class="form-input" placeholder="Full Name" required>
            <input type="email" name="email" class="form-input" placeholder="Email" required>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
            <a href="s-login.php">Already have an account?</a>
        </form>
    </div>
</body>
</html>