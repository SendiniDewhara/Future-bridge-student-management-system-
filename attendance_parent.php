<?php
session_start();
include('db_connection.php'); // Database connection

// Check if the user is a parent (user_type_id = 3)
if (!isset($_SESSION['user_type_id']) || $_SESSION['user_type_id'] != 3) {
    die("Access Denied! Only parents can view attendance.");
}

// Get the parent ID from the session
$parent_id = $_SESSION['user_id'];

// Fetch the student's ID(s) linked to this parent
$stmt = $pdo->prepare("SELECT student_id FROM users WHERE user_id = :parent_id");
$stmt->execute(['parent_id' => $parent_id]);

// If no student is linked, display an error
if ($stmt->rowCount() === 0) {
    die("No student linked to your account.");
}

// Fetch the student IDs
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Extract student IDs for querying attendance
$studentIds = array_column($students, 'student_id');

// Fetch attendance data for the students linked to this parent
$attendanceQuery = $pdo->prepare("
    SELECT attendance.date, attendance.status, students.first_name AS student_name
    FROM attendance
    JOIN students ON attendance.student_id = students.student_id
    WHERE attendance.student_id IN (" . implode(',', $studentIds) . ")
    ORDER BY attendance.date DESC
");
$attendanceQuery->execute();
$attendanceData = $attendanceQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6f0ff; /* Light blue background for the page */
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 50px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1000px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #004080; /* Blue color for the header text */
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #004080; /* Dark blue header for the table */
            color: white;
        }

        .status {
            font-weight: bold;
        }

        .status.present {
            color: green;
        }

        .status.absent {
            color: red;
        }

        .status.late {
            color: orange;
        }

        /* Additional styling for the page's background */
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('background.jpg'); /* Add your own background image here */
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            z-index: -1;
        }
    </style>
</head>
<body>

<!-- Add a background overlay if desired -->
<div class="background-overlay"></div>

<div class="container">
    <h2>Your Children's Attendance</h2>
    
    <?php if (empty($attendanceData)): ?>
        <p>No attendance records found for your children.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceData as $attendance): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($attendance['date']); ?></td>
                        <td><?php echo htmlspecialchars($attendance['student_name']); ?></td>
                        <td class="status <?php echo strtolower($attendance['status']); ?>">
                            <?php echo ucfirst($attendance['status']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
