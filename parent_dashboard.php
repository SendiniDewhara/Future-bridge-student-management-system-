<?php
session_start();
if ($_SESSION['user_type_id'] != 3) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
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
    </style>
</head>
<body>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="child_performance.php">Child's Performance</a>
        <a href="attendance.php">Attendance</a>
        <a href="fee_payments.php">Fee Payments</a>
        <a href="term_marks.php">Term Marks</a>
        <a href="extracurricular.php">Extracurricular Activities</a>
        <a href="behavior.php">Behavior & Mental Health</a>
        <a href="gov_exam_results.php">Government Exam Results</a>
        <a href="documents.php">Student Documents</a>
        <a href="logout.php" class="logout">Logout</a>
    </nav>

    <div class="container">
        <h1>Welcome Parent</h1>
        <p>Monitor your child's progress and stay updated with their school activities.</p>

        <div class="feature-section">
            <h2>Parent Features:</h2>
            <ul>
                <li><a href="child_performance.php">Child's Performance</a> - View academic and extracurricular performance.</li>
                <li><a href="attendance.php">Attendance</a> - Track your child's attendance records.</li>
                <li><a href="fee_payments.php">Fee Payments</a> - View and manage fee payment records.</li>
                <li><a href="term_marks.php">Term Marks</a> - Check term test results.</li>
                <li><a href="extracurricular.php">Extracurricular Activities</a> - View participation in extracurricular activities.</li>
                <li><a href="behavior.php">Behavior & Mental Health</a> - Monitor behavior and mental health updates.</li>
                <li><a href="gov_exam_results.php">Government Exam Results</a> - Check government exam results.</li>
                <li><a href="documents.php">Student Documents</a> - Access important student-related documents.</li>
            </ul>
        </div>
    </div>
</body>
</html>
