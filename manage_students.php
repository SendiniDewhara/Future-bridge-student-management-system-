<?php
session_start();
if ($_SESSION['user_type_id'] != 2) { // Ensure only school admins can access
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

// Fetch batches for dropdown
$batch_query = "SELECT DISTINCT batch FROM students ORDER BY batch";
$batch_result = $conn->query($batch_query);

// Fetch student data based on selected batch
$selected_batch = isset($_GET['batch']) ? $_GET['batch'] : '';
$sql = "SELECT * FROM students";
if (!empty($selected_batch)) {
    $sql .= " WHERE batch = '" . $conn->real_escape_string($selected_batch) . "'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        nav {
            background-color: #2c3e50;
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #2c3e50;
            color: white;
        }
        .filter {
            margin: 20px 0;
        }
        .filter select {
            padding: 5px;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="manage_students.php">Manage Students</a>
            <a href="add_student.php">Add Student</a>
        </div>
        <div>Welcome, Admin</div>
    </nav>

    <h1>Manage Students</h1>

    <div class="filter">
        <form method="GET" action="">
            <label for="batch">Filter by Batch:</label>
            <select name="batch" id="batch" onchange="this.form.submit()">
                <option value="">-- All Batches --</option>
                <?php if ($batch_result->num_rows > 0): ?>
                    <?php while ($batch = $batch_result->fetch_assoc()): ?>
                        <option value="<?php echo $batch['batch']; ?>" <?php echo ($batch['batch'] == $selected_batch) ? 'selected' : ''; ?>>
                            <?php echo $batch['batch']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Guardian Name</th>
                <th>Batch</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['dob']; ?></td>
                        <td><?php echo $row['guardian_name']; ?></td>
                        <td><?php echo $row['batch']; ?></td>
                        <td>
                            <a href="edit_student.php?id=<?php echo $row['student_id']; ?>">Edit</a> |
                            <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
