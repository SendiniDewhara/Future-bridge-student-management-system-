<?php
session_start();
if ($_SESSION['user_type_id'] != 2) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_management_system'; 
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $guardian_name = $_POST['guardian_name'];

    $sql = "INSERT INTO students (first_name, last_name, gender, dob, guardian_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $gender, $dob, $guardian_name);

    if ($stmt->execute()) {
        header("Location: manage_students.php");
        exit();
    } else {
        echo "Error adding student: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
</head>
<body>
    <h1>Add New Student</h1>
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" required><br>
        <label>Last Name:</label>
        <input type="text" name="last_name" required><br>
        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br>
        <label>Date of Birth:</label>
        <input type="date" name="dob" required><br>
        <label>Guardian Name:</label>
        <input type="text" name="guardian_name" required><br>
        <button type="submit">Add Student</button>
    </form>
</body>
</html>
