<?php
/*session_start();
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_type_id'] = $user['user_type_id'];
        
        // Redirect to respective dashboard
        if ($user['user_type_id'] == 1) {
            header("Location: nursery_admin_dashboard.php");
        } elseif ($user['user_type_id'] == 2) {
            header("Location: school_admin_dashboard.php");
        } else {
            header("Location: parent_dashboard.php");
        }
        exit();
    } else {
        echo "<p style='color: red;'>Invalid credentials!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4b79a1, #283e51); /* Dark blue gradient background */
           /* display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2); /* Transparent white */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px); /* Blurred background effect */
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
            border-color: #004080; /* Dark blue border on focus */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #004080; /* Dark blue button */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #003366; /* Darker blue on hover */
        }

        .forgot-password {
            color: #004080; /* Dark blue link */
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>*/
/*<body>

    <div class="login-container">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Enter your username" required><br>
            <input type="password" name="password" placeholder="Enter your password" required><br>
            <button type="submit">Login</button>
        </form>
        <a href="#" class="forgot-password">Forgot password?</a>
        <?php if (isset($errorMessage)) { ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php } ?>
    </div>

</body>
</html>*/
