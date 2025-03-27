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
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4b79a1, #283e51);
            margin: 0;
            padding: 0;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        nav {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
        }
        nav::-webkit-scrollbar {
            width: 6px;
        }
        nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 10px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 12px;
            font-size: 16px;
            display: block;
            border-radius: 8px;
            transition: 0.3s;
        }
        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .logout {
            background-color: #e74c3c;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .logout:hover {
            background-color: #c0392b;
        }
        .container {
            padding: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            max-width: 700px;
            width: 100%;
            text-align: center;
            margin-left: 270px;
            min-height: 80vh;
            overflow: auto;
        }
        h1 {
            font-size: 36px;
            margin-top: 0;
        }
        p {
            font-size: 18px;
        }
        img {
            width: 100%;
            border-radius: 10px;
            margin-top: 10px;
            max-height: 300px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    
    <nav>
        <a href="manage_students.php">Manage Students</a>
        <a href="attendance.php">Attendance</a>
        <a href="timetable.php">Timetable</a>
        <a href="marks.php">Term Marks</a>
        <a href="extracurricular.php">Extracurricular Activities</a>
        <a href="fee_payments.php">Fee Payments</a>
        <a href="textbooks.php">Textbook Distribution</a>
        <a href="gov_exam_results.php">Government Exam Results</a>
        <a href="add_behaviour.php">Behavior</a>
        <a href="mental_health.php">Mental Health</a>
        <a href="documents.php">Student Documents</a>
        <a href="homework.php">Homework</a>
        <a href="parent_register.php">Register Parent</a>
        <a href="view_parents.php">View Parents</a>

        <a href="logout.php" class="logout">Logout</a>
    </nav>
    
    <div class="container">
        <h1>Welcome, School Admin</h1>
        <p>Manage all aspects of the school and student activities from this dashboard.</p>
        <img src="images/school_image.jpg" alt="School Image">
    </div>
    
</body>
</html>
