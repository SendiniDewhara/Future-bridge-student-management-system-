<?php
session_start();
require 'vendor/autoload.php';  // Include Composer's autoload file

$host = 'localhost';  // MySQL server host (typically localhost for local servers like XAMPP)
$username = 'root';   // MySQL username (default for XAMPP)
$password = '';       // MySQL password (default is empty for XAMPP)
$db_name = 'student_management_system';  // The name of your database

try {
    // Create a PDO instance with the provided connection details
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, show the error message and stop script execution
    echo "Connection failed: " . $e->getMessage();
    exit;  // Stop further execution if the connection is not established
}

// Ensure the user is logged in and is a parent (user_type_id = 3)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type_id'] != 3) {
    die("Access Denied! Only parents can view the timetable.");
}

// Get the logged-in parent's user_id
$parent_id = $_SESSION['user_id'];

// Fetch the student linked to the parent
$stmt = $pdo->prepare("SELECT student_id FROM users WHERE user_id = :parent_id");
$stmt->execute(['parent_id' => $parent_id]);
$parentData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the parent has a linked student
if (!$parentData || empty($parentData['student_id'])) {
    die("No student linked to your account.");
}

$student_id = $parentData['student_id']; // Get student_id

// Fetch the batch ID of the student's batch
$batchStmt = $pdo->prepare("SELECT batch_id FROM students WHERE student_id = :student_id");
$batchStmt->execute(['student_id' => $student_id]);
$batchData = $batchStmt->fetch(PDO::FETCH_ASSOC);

// Ensure the student has a batch assigned
if (!$batchData || empty($batchData['batch_id'])) {
    die("No batch assigned to the student.");
}

$batch_id = $batchData['batch_id']; // Get batch_id

// Fetch timetable data for the student's batch
$timetableQuery = $pdo->prepare("
    SELECT t.day_of_week, t.start_time, t.end_time, t.subject, 
           b.batch_name 
    FROM timetable t
    JOIN batches b ON t.batch_id = b.batch_id
    WHERE t.batch_id = :batch_id
    ORDER BY t.day_of_week, t.start_time ASC
");
$timetableQuery->execute(['batch_id' => $batch_id]);
$timetableData = $timetableQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Child's Timetable</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .table {
            margin-top: 20px;
            background-color: #ffffff;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Your Child's Timetable</h1>

    <?php if (empty($timetableData)): ?>
        <p>No timetable records found for your child.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Day of Week</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Subject</th>
                    <th>Batch</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timetableData as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['day_of_week']); ?></td>
                        <td><?php echo htmlspecialchars($entry['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($entry['end_time']); ?></td>
                        <td><?php echo htmlspecialchars($entry['subject']); ?></td>
                        <td><?php echo htmlspecialchars($entry['batch_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
