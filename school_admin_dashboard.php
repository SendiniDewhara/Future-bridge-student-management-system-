<?php
session_start();
if ($_SESSION['user_type_id'] != 2) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
        }
        nav {
            background-color: #2c3e50;
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            font-size: 16px;
            display: block;
        }
        nav a:hover {
            background-color: #34495e;
        }
        nav .logout {
            background-color: #e74c3c;
            margin: 20px 20px 0 auto;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
        }
        nav .logout:hover {
            background-color: #c0392b;
        }
        .container {
            margin-left: 250px; /* Adjust content to account for sidebar width */
            padding: 20px;
            width: calc(100% - 250px);
        }
        h1 {
            color: #333;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
        }
        .feature {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_students.php">Manage Students</a>
        <a href="attendance.php">Attendance</a>
        <a href="marks.php">Term Marks</a>
        <a href="extracurricular.php">Extracurricular Activities</a>
        <a href="fee_payments.php">Fee Payments</a>
        <a href="textbooks.php">Textbook Distribution</a>
        <a href="gov_exam_results.php">Government Exam Results</a>
        <a href="add_behaviour.php">Behavior </a>
        <a href="mental_health.php">Mental health </a>
        <a href="documents.php">Student Documents</a>
        <a href="logout.php" class="logout">Logout</a>
    </nav>

    <div class="container">
        <h1>Welcome School Admin</h1>
        <p>Manage all aspects of the school and student activities from this dashboard.</p>
        <img src="school_image.jpg" alt="School Image">

        
        </div>
    </div>
</body>
</html>
