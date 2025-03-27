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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4b79a1, #283e51);
            margin: 0;
            padding: 0;
            color: #fff;
        }
        nav {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .logout {
            background-color: #e74c3c;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .logout:hover {
            background-color: #c0392b;
        }
        .container {
            padding: 20px;
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            max-width: 1200px;
            margin: 30px auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        .content-left, .content-right {
            width: 48%;
            margin-top: 20px;
        }
        h1 {
            color: #fff;
            text-align: center;
            font-size: 36px;
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #fff;
        }
        .feature-section {
            margin-top: 40px;
        }
        .feature {
            background: rgba(255, 255, 255, 0.4);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .feature h3 {
            color: #004080;
        }
        img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .content-left, .content-right {
                width: 100%;
            }
            .nav-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

    <nav>
        <div class="nav-links">
            <a href="create_student.php">Create Student Account</a>
            <a href="attendance.php">Attendance</a>
            <a href="add_behaviour.php">Add Behaviour</a>
            <a href="mental_health.php">Mental Health</a>
            <a href="special_needs.php">Special Needs</a>
            <a href="marks.php">Marks</a>
            <a href="timetable.php">Timetable</a>
            <a href="homework.php">Homework</a>
            <a href="fee_payments.php">Fee Payments</a>
            <a href="parent_register.php">Register Parent</a>
            <a href="view_parents.php">View Parents</a>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </nav>

    <div class="container">
        <div class="content-left">
            <img src="images/nursery_image.jpg" alt="Nursery Image">
        </div>
        <div class="content-right">
            <h1>Welcome Nursery Admin</h1>
            <p>Manage all aspects of the nursery efficiently from this dashboard.</p>
            <div class="feature-section">
                <div class="feature">
                    <h3>Manage Student Accounts</h3>
                    <p>Handle student registration, updates, and deletions with ease.</p>
                </div>
                <div class="feature">
                    <h3>Track Mental Health</h3>
                    <p>Monitor the mental well-being of students and provide necessary support.</p>
                </div>
                <div class="feature">
                    <h3>Marks and Timetable</h3>
                    <p>Manage student marks and keep track of their class schedules.</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>