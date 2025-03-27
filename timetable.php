<?php
session_start();
require 'vendor/autoload.php'; // Include Composer's autoload file

$host = 'localhost'; // MySQL server host
$username = 'root'; // MySQL username
$password = ''; // MySQL password
$db_name = 'student_management_system'; // The name of  database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

if (isset($_POST['submit'])) {
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $subject = $_POST['subject'];
    $batch_id = $_POST['batch'];

    try {
        $stmt = $pdo->prepare("INSERT INTO timetable (day_of_week, start_time, end_time, subject, batch_id) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$day_of_week, $start_time, $end_time, $subject, $batch_id]);

        // Insert a notification for the parent
        if (isset($_SESSION['user_id'])) {
            $parent_id = $_SESSION['user_id'];
            $notification_message = "New timetable added: $subject on $day_of_week at $start_time.";
            $stmt_notification = $pdo->prepare("INSERT INTO notifications (user_id, message, created_at, is_read) 
                                               VALUES (?, ?, NOW(), 0)");
            $stmt_notification->execute([$parent_id, $notification_message]);
        }

        echo "Timetable uploaded successfully and notification sent!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$batchQuery = $pdo->query("SELECT batch_id, batch_name FROM batches");
$batches = $batchQuery->fetchAll(PDO::FETCH_ASSOC);

$timetableQuery = $pdo->query("SELECT t.timetable_id, t.day_of_week, t.start_time, t.end_time, t.subject, b.batch_name 
                               FROM timetable t
                               JOIN batches b ON t.batch_id = b.batch_id");
$timetableData = $timetableQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Timetable</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color:rgb(59, 104, 151); /* Blue background */
            color: white; /* White text */
            font-family: Arial, sans-serif;
            padding-top: 50px;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group label {
            font-size: 16px;
        }

        .form-control {
            background-color: transparent; /* Transparent background */
            color: white; /* White text */
            border: 1px solid white; /* White border */
            border-radius: 4px;
            box-shadow: none;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color:rgb(80, 119, 161); /* Focused border color */
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5); /* Focused box shadow */
        }

        .btn-primary {
            background-color: #0056b3; /* Dark blue button */
            border-color: #0056b3;
        }

        .btn-primary:hover {
            background-color:rgb(100, 139, 177); /* Darker blue button on hover */
            border-color:rgb(84, 125, 167);
        }

        .table {
            background-color: rgba(255, 255, 255, 0.3); /* Transparent white background */
            color: #333333; /* Dark text for the table */
        }

        .table-bordered {
            border: 1px solid rgba(73, 132, 196, 0.8); /* Semi-transparent blue border */
        }

        .table th, .table td {
            color: #333; /* Dark text for table cells */
        }

        .table th {
            background-color:rgb(72, 120, 170); /* Blue header */
            color: white;
        }

        .btn {
            margin: 5px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(0, 0, 0, 0.4); /* Slight black overlay for better readability */
            padding: 30px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Upload Timetable</h1>
    <form method="POST">
        <div class="form-group">
            <label for="day_of_week">Day of the Week</label>
            <input type="text" name="day_of_week" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="batch">Select Batch</label>
            <select name="batch" class="form-control" required>
                <option value="">Select Batch</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?php echo $batch['batch_id']; ?>">
                        <?php echo htmlspecialchars($batch['batch_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
    </form>

    <h2>Existing Timetable Entries</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Day of Week</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Subject</th>
                <th>Batch</th>
                <th>Actions</th>
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
                    <td>
                        <a href="editTimetable.php?timetable_id=<?php echo $entry['timetable_id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="deleteTimetable.php?timetable_id=<?php echo $entry['timetable_id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
