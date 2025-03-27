<?php
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, user_type_id) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $user_type]);
        
        // Redirect based on user type
        if ($user_type == 1) {
            echo "<script>alert('Registration successful! You can now login as Nursery Admin.'); window.location.href='nursery_admin_login.php';</script>";
        } elseif ($user_type == 2) {
            echo "<script>alert('Registration successful! You can now login as School Admin.'); window.location.href='school_admin_login.php';</script>";
        }
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
            background-color: #1e3c72; /* Blue background */
            color: #fff; /* White text */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 600px;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .image-section {
            flex: 1;
            background: url('images/register.jpg') no-repeat center center;
            background-size: contain; /* Ensures the full image is displayed */
            position: relative;
            opacity: 0.7; /* Slight transparency to image */
        }

        .form-section {
            flex: 1;
            padding: 40px;
        }

        .form-section h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
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
            background: linear-gradient(to right,rgb(92, 155, 184),rgb(62, 128, 182));
            color: #fff;
            cursor: pointer;
            border: none;
        }

        .form-section form button:hover {
            background: linear-gradient(to right,rgb(76, 143, 174),rgb(82, 160, 190));
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
                </select>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
