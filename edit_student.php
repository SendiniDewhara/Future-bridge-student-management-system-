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
$database = 'student_management_system'; // Replace with your actual database name
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data
$student_id = $_GET['id'];
$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $guardian_name = $_POST['guardian_name'];

    $update_sql = "UPDATE students SET first_name = ?, last_name = ?, gender = ?, dob = ?, guardian_name = ? WHERE student_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $first_name, $last_name, $gender, $dob, $guardian_name, $student_id);

    if ($update_stmt->execute()) {
        header("Location: manage_students.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo $student['first_name']; ?>" required><br>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $student['last_name']; ?>" required><br>
        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?php if ($student['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($student['gender'] == 'Female') echo 'selected'; ?>>Female</option>
        </select><br>
        <label>Date of Birth:</label>
        <input type="date" name="dob" value="<?php echo $student['dob']; ?>" required><br>
        <label>Guardian Name:</label>
        <input type="text" name="guardian_name" value="<?php echo $student['guardian_name']; ?>" required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
