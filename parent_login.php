<?php 
session_start(); 
include('db_connection.php'); // Include the database connection file  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {     
    $username = trim($_POST['username']);     
    $password = $_POST['password'];      

    try {         
        // Ensure only parents (user_type_id = 3) can log in         
        $query = "SELECT * FROM users WHERE username = :username AND user_type_id = 3";          
        $stmt = $pdo->prepare($query);         
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);         
        $stmt->execute();          

        if ($stmt->rowCount() == 1) {             
            $user = $stmt->fetch(PDO::FETCH_ASSOC);              

            // Verify the password             
            if (password_verify($password, $user['password'])) {                 
                session_regenerate_id(true); // Prevent session fixation                 
                $_SESSION['user_id'] = $user['user_id'];                 
                $_SESSION['student_id'] = $user['student_id']; // Link parent to student                 
                $_SESSION['username'] = $user['username'];                 
                $_SESSION['user_type_id'] = $user['user_type_id']; // Store user type                  

                header("Location: parent_dashboard.php");                 
                exit();             
            } else {                 
                $error = "Invalid password.";             
            }         
        } else {             
            $error = "Access denied. Only parents can log in.";         
        }     
    } catch (PDOException $e) {         
        $error = "An error occurred: " . $e->getMessage();     
    } 
} 
?> 

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Parent Login</title>     
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

        .forgot-password {             
            color: #004080;             
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
</head> 
<body>      

    <div class="login-container">         
        <h2>Parent Login</h2>         
        <?php if (isset($error)) echo "<p class='error-message'>" . htmlspecialchars($error) . "</p>"; ?>         
        <form method="POST">             
            <input type="text" name="username" placeholder="Enter your username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required><br>             
            <input type="password" name="password" placeholder="Enter your password" required><br>             
            <button type="submit">Login</button>         
        </form>         
        <div class="forgot-password">             
            <a href="#" onclick="alert('Please contact the admin to reset your password.'); return false;">Forgot Password?</a>         
        </div>     
    </div>  

</body> 
</html>
