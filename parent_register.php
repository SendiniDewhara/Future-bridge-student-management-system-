<?php
session_start();
include('db_connection.php'); // Database connection

// Check if the user is a school or nursery admin
if (!isset($_SESSION['user_type_id']) || ($_SESSION['user_type_id'] != 1 && $_SESSION['user_type_id'] != 2)) {
    die("Access Denied! Only admins can register parents.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // Get form inputs
        $parentUsername = $_POST['parent_username'];
        $parentPassword = password_hash($_POST['parent_password'], PASSWORD_DEFAULT);
        $studentId = $_POST['student_id']; // Link to student

        // Check if username exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $parentUsername]);

        if ($stmt->rowCount() > 0) {
            throw new Exception("The username '$parentUsername' is already taken. Please choose another.");
        }

        // Verify Student Exists
        $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = :student_id");
        $stmt->execute([':student_id' => $studentId]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Invalid Student ID: No student found.");
        }

        // Insert Parent Record (Removed created_by_admin)
        $stmt = $pdo->prepare("INSERT INTO users (username, password, user_type_id, student_id) 
                               VALUES (:username, :password, 3, :student_id)");
        $stmt->execute([ 
            ':username' => $parentUsername,
            ':password' => $parentPassword,
            ':student_id' => $studentId
        ]);

        $pdo->commit();
        $success = "Parent account successfully created and linked to Student ID: $studentId.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Parent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, rgb(103, 134, 167), #0056b3);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: rgb(98, 136, 177);
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-size: 14px;
        }
        input {
            padding: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        button {
            padding: 12px;
            font-size: 16px;
            background-color: rgb(85, 123, 163);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: rgb(60, 83, 107);
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
        }
        .message p {
            font-size: 14px;
        }
        .message p.success {
            color: green;
        }
        .message p.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Parent</h2>
        <div class="message">
            <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </div>
        <form method="POST">
            <label for="parent_username">Parent Username:</label>
            <input type="text" name="parent_username" required>
            <label for="parent_password">Parent Password:</label>
            <input type="password" name="parent_password" required>
            <label for="student_id">Student ID (Link to):</label>
            <input type="number" name="student_id" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
