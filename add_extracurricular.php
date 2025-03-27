<?php
// Database connection
$host = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "student_management_system"; // Update with your actual database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $activity_name = $_POST['activity_name'];
    $participation_level = $_POST['participation_level'];
    $last_participation_date = $_POST['last_participation_date'];
    $status = $_POST['status'];

    // Insert data into database
    $sql = "INSERT INTO extracurricular_activities (student_id, activity_name, participation_level, last_participation_date, status) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $student_id, $activity_name, $participation_level, $last_participation_date, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Activity added successfully!'); window.location.href='';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Extracurricular Activity</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="form-container">
        <h2>Add Extracurricular Activity</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Student ID</label>
                <input type="number" name="student_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Activity Name</label>
                <input type="text" name="activity_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Participation Level</label>
                <select name="participation_level" class="form-select" required>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Participation Date</label>
                <input type="date" name="last_participation_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Activity</button>
        </form>
    </div>
</div>
</body>
</html>
