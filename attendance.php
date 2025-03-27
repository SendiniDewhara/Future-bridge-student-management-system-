<?php
// student_attendance.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #003973, #E5E5BE);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 320px;
        }
        h2 {
            color: #fff;
            font-size: 22px;
            margin-bottom: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            display: block;
            background: rgba(255, 255, 255, 0.3);
            padding: 12px;
            border-radius: 8px;
            color: #003973;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        a:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Attendance Management</h2>
        <ul>
            <li><a href="insert_attendance.php">Insert Attendance</a></li>
            <li><a href="view_attendance.php">View Attendance</a></li>
        </ul>
    </div>
</body>
</html>
