<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_type_id']) || ($_SESSION['user_type_id'] != 1 && $_SESSION['user_type_id'] != 2)) {
    die("Access Denied! Only admins can view parents.");
}

// Updated query: Only fetch details of parent accounts (user_type_id = 3)
$query = "SELECT u.user_id, u.username, s.student_id, 
                 CONCAT(s.first_name, ' ', s.last_name) AS student_name
          FROM users u
          JOIN students s ON u.student_id = s.student_id
          WHERE u.user_type_id = 3"; // Only Parent Accounts

$stmt = $pdo->prepare($query);
$stmt->execute();
$parents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registered Parents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
        h2 {
            color: #ffffff;
            background-color:rgb(107, 147, 189);
            padding: 10px;
            text-align: center;
            margin: 0;
            border-bottom: 2px solid #0056b3;
        }
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color:rgb(71, 120, 173);
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .table-container {
            width: 90%;
            overflow-x: auto;
            padding: 20px;
        }
        .message {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Parents</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Parent ID</th>
                    <th>Username</th>
                    <th>Linked Student ID</th>
                    <th>Linked Student Name</th>
                </tr>
                <?php foreach ($parents as $parent): ?>
                <tr>
                    <td><?php echo $parent['user_id']; ?></td>
                    <td><?php echo $parent['username']; ?></td>
                    <td><?php echo $parent['student_id']; ?></td>
                    <td><?php echo $parent['student_name']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
