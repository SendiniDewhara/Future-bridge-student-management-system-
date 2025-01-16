<?php
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, user_type_id) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $user_type]);
        echo "<script>alert('Registration successful! You can now login.'); window.location.href='login.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 900px;
            background-color: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .image-section {
            flex: 1;
            background: url('image.png') no-repeat center center/cover;
            position: relative;
        }

        .form-section {
            flex: 1;
            padding: 40px;
        }

        .form-section h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-section form {
            display: flex;
            flex-direction: column;
        }

        .form-section form input,
        .form-section form select,
        .form-section form button {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-section form button {
            background: linear-gradient(to right, #5cb85c, #4cae4c);
            color: #fff;
            cursor: pointer;
            border: none;
        }

        .form-section form button:hover {
            background: linear-gradient(to right, #4cae4c, #5cb85c);
        }

        .form-section .login-link {
            text-align: center;
            margin-top: 10px;
        }

        .form-section .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .form-section .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section"></div>
        <div class="form-section">
            <h2>Register</h2>
            <form method="post">
                <input type="text" name="username" placeholder="Full Name" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="user_type" required>
                    <option value="1">Nursery Admin</option>
                    <option value="2">School Admin</option>
                    <option value="3">Parent</option>
                </select>
                <button type="submit">Register</button>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Log in</a></p>
            </div>
        </div>
    </div>
</body>
</html>
