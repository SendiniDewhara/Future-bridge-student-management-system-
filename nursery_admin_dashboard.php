<?php
session_start();
if ($_SESSION['user_type_id'] != 1) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nursery Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        h1 {
            color: #333;
        }
        nav {
            background-color: #2c3e50;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .logout {
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #c0392b;
        }
        .container {
            margin-top: 20px;
        }
        .feature-section {
            margin-top: 30px;
        }
        .feature {
            margin: 10px 0;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="create_student.php">Create Student Account</a>
            <a href="add_behaviour.php">Add Behaviour</a>
            <a href="mental_health.php">Mental Health</a>
            <a href="special_needs.php">Special Needs</a>
            <a href="marks.php">Marks</a>
            <a href="timetable.php">Timetable</a>
            <a href="homework.php">Homework</a>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </nav>

    <div class="container">
        <h1>Welcome Nursery Admin</h1>
        <p>Manage all aspects of the nursery efficiently from this dashboard.</p>
        <img src="nursery_image.jpg" alt="Nursery Image">

        
        </div>
    </div>
</body>
</html>
