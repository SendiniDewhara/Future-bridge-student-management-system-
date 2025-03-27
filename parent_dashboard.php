<?php
session_start();
require 'vendor/autoload.php'; // Include Composer's autoload file

$host = 'localhost'; // MySQL server host
$username = 'root'; // MySQL username
$password = ''; // MySQL password
$db_name = 'student_management_system'; // The name of your database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check user type
if (!isset($_SESSION['user_id']) || $_SESSION['user_type_id'] != 3) {
    header("Location: login.php");
    exit();
}

// Fetch unread notifications
$parent_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
$stmt->execute([$parent_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, rgba(104, 152, 204, 0.7), rgba(69, 150, 204, 0.7));
            margin: 0;
            display: flex;
        }
        nav {
            background: rgba(81, 123, 168, 0.9);
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            overflow-y: auto; /* Allow vertical scrolling */
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            font-size: 16px;
            display: block;
            transition: 0.3s;
        }
        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }
        .container {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
            position: relative;
        }
        .notification-popup {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: none;
        }
    </style>
</head>
<body>
    <nav>
        <a href="child_performance.php">Child's Performance</a>
        <a href="attendance_parent.php">Attendance</a>
        <a href="timetable_parent.php">Timetable</a>
        <a href="parent_homework.php">Homework</a>
        <a href="fee_parent.php">Fee Payments</a>
        <a href="parent_marks.php">Term Marks</a>
        <a href="parent_extracurricular.php">Extracurricular Activities</a>
        <a href="parent_behaviour.php">Behavior</a>
        <a href="view_mental.php">Mental health status</a>
        <a href="parent_gov_exam_results.php">Government Exam Results</a>
        <a href="textbook_parent.php">Distribution</a>
        <a href="parent_document.php">Student Documents</a>
        <a href="notifications.php">View Notifications</a>
        <a href="parent_profile.php">View Profile</a>
        <a href="logout.php" class="logout">Logout</a>
    </nav>

    <div class="container">
        <h1>Welcome Parent</h1>
        <p>Monitor your child's progress and stay updated with their school activities.</p>
        <img src="images/backgroundparent.jpg" alt="Parent Dashboard Image" class="img-responsive" style="border-radius: 8px;">

        <?php if (!empty($notifications)): ?>
            <div class="notification-popup" id="notificationPopup">
                <p><strong>New Notifications:</strong></p>
                <ul>
                    <?php foreach ($notifications as $notification): ?>
                        <li><?php echo htmlspecialchars($notification['message']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button onclick="closeNotification()" class="btn btn-danger btn-sm">Close</button>
            </div>
            <script>
                $(document).ready(function() {
                    $('#notificationPopup').fadeIn();
                });

                function closeNotification() {
                    $('#notificationPopup').fadeOut();
                    $.ajax({
                        url: 'mark_notifications_read.php',
                        method: 'POST'
                    });
                }
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
