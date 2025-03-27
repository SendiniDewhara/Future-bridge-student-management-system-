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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            width: 350px;
            text-align: center;
        }

        h1 {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 14px;
        }

        input, select, button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
        }

        input, select {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        select {
            cursor: pointer;
        }

        button {
            background: #4CAF50;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo $student['first_name']; ?>" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $student['last_name']; ?>" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?php if ($student['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($student['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            </select>

            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?php echo $student['dob']; ?>" required>

            <label>Guardian Name:</label>
            <input type="text" name="guardian_name" value="<?php echo $student['guardian_name']; ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
