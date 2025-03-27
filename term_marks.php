<?php
session_start();
include('db_connection.php'); // Include database connection file

// Check if parent is logged in
if (!isset($_SESSION['parent_id'])) {
    echo "Session not set. Please log in again.";
    header("refresh:2; url=login.php"); // Redirect after 2 seconds
    exit();
}

$parent_id = $_SESSION['parent_id'];

// Debugging: Print session values (REMOVE this after testing)
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Fetch student's marks linked to this parent
$query = "SELECT sm.*, s.student_name 
          FROM student_marks sm 
          JOIN students s ON sm.student_id = s.student_id 
          WHERE s.parent_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Parent View - Term Marks</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Term Marks</h1>

    <h2>Uploaded Marks</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Term</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['marks']); ?></td>
                    <td><?php echo htmlspecialchars($row['term']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
