<?php
require 'db_connection.php'; // Include database connection

// Check if timetable ID is provided
if (isset($_GET['timetable_id'])) {
    $timetable_id = $_GET['timetable_id'];

    // Fetch the existing timetable data
    $stmt = $pdo->prepare("SELECT * FROM timetable WHERE timetable_id = ?");
    $stmt->execute([$timetable_id]);
    $timetable = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$timetable) {
        echo "Timetable entry not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $subject = $_POST['subject'];

    try {
        $stmt = $pdo->prepare("UPDATE timetable SET student_id = ?, day_of_week = ?, start_time = ?, end_time = ?, subject = ? WHERE timetable_id = ?");
        $stmt->execute([$student_id, $day_of_week, $start_time, $end_time, $subject, $timetable_id]);

        echo "Timetable updated successfully!";
        header("Location: timetable.php"); // Redirect back to the timetable page
        exit;
    } catch (PDOException $e) {
        echo "Error updating timetable: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Timetable</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #3b4d66; /* Lighter dark blue background */
            color: white; /* White text */
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            color: #f1f1f1;
        }

        .form-group label {
            color: #f1f1f1;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.2); /* Transparent background for form inputs */
            border: 1px solid #f1f1f1;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Timetable</h1>
    <form method="POST">
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" name="student_id" class="form-control" value="<?php echo htmlspecialchars($timetable['student_id']); ?>" required>
        </div>
        <div class="form-group">
            <label for="day_of_week">Day of Week</label>
            <input type="text" name="day_of_week" class="form-control" value="<?php echo htmlspecialchars($timetable['day_of_week']); ?>" required>
        </div>
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" class="form-control" value="<?php echo htmlspecialchars($timetable['start_time']); ?>" required>
        </div>
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" class="form-control" value="<?php echo htmlspecialchars($timetable['end_time']); ?>" required>
        </div>
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($timetable['subject']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="timetable.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
