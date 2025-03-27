<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "student_management_system";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT u.user_id, u.username, u.password, u.user_type_id, ut.user_type_name 
            FROM users u
            JOIN user_types ut ON u.user_type_id = ut.user_type_id
            WHERE u.username = ? AND ut.user_type_name = 'nursery_admin'";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $user_username, $user_password, $user_type_id, $user_type_name);

        if ($stmt->fetch() && password_verify($password, $user_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $user_username;
            $_SESSION['user_type_id'] = $user_type_id;
            $_SESSION['user_type_name'] = $user_type_name;
            header("Location: nursery_admin_dashboard.php");
            exit();
        } else {
            $errorMessage = "Invalid username or password.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nursery Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4b79a1, #283e51);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: #fff;
        }

        .login-container h2 {
            font-size: 28px;
            color: #fff;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.7);
            color: #333;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #004080;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #004080;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #003366;
        }

        .forgot-password, .register-link {
            color: #004080;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
        }

        .forgot-password:hover, .register-link:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Nursery Admin Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter your username" required><br>
            <input type="password" name="password" placeholder="Enter your password" required><br>
            <button type="submit">Login</button>
        </form>
        <a href="#" class="forgot-password">Forgot password?</a>
        <a href="register.php" class="register-link">Register here</a>
        <?php if (isset($errorMessage)) { ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php } ?>
    </div>
</body>
</html>
