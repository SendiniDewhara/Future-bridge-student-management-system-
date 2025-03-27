<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type_id'] != 3) {
    die("Access Denied!");
}

$parent_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $parent_id]);
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle Password Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match. Please try again.</p>";
    } else {
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE users SET password = :password WHERE user_id = :user_id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([':password' => $new_password_hashed, ':user_id' => $parent_id]);
        echo "<p>Password updated successfully!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Profile</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #f0f8ff; /* Light blue background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Main Container */
        .container {
            background: rgba(255, 255, 255, 0.9); /* White with transparency */
            border-radius: 10px;
            padding: 30px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Header */
        h2 {
            color: #007BFF; /* Blue text for title */
        }

        /* Button and Inputs */
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF; /* Blue button */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Paragraph for message */
        p {
            color: green;
            font-weight: bold;
        }

        /* Error message */
        p.error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Parent Profile</h2>
        <p>Username: <?php echo htmlspecialchars($parent['username']); ?></p>

        <h3>Change Password</h3>
        <form method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>
