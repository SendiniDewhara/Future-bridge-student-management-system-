<?php
session_start();
$host = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "student_management_system";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access Denied. Please log in.");
}

$user_id = $_SESSION['user_id'];

// Get the user's role
$sql = "SELECT user_type_id, student_id FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_type_id = $user['user_type_id'];
$student_id = $user['student_id']; // Parent's child ID
$stmt->close();

// If the user is a parent, fetch only their child's behavior records
if ($user_type_id == 3) { // Parent (assuming user_type_id '3' is for parents)
    $sql = "SELECT sb.behavior_id, sb.behavior_notes
            FROM student_behavior sb
            WHERE sb.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
} else { // Admin or other users can view all records
    $sql = "SELECT sb.behavior_id, sb.behavior_notes, 
                   s.first_name, s.last_name
            FROM student_behavior sb
            INNER JOIN students s ON sb.student_id = s.student_id";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Behavior Records</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #4682b4; /* Blue background for the entire page */
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            background: rgba(255, 255, 255, 0.9); /* Transparent white background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
        }
        h2 {
            color: rgb(83, 145, 212); /* Blue color for heading */
            font-size: 32px;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: rgb(82, 119, 158); /* Blue background for table headers */
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .btn {
            background: rgb(63, 108, 156);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn:hover {
            background: rgb(52, 105, 161);
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Student Behavior Records</h2>
        
        <table>
            <tr>
                <th>Behavior Notes</th>
                <?php if ($user_type_id != 3) { echo "<th>Student Name</th>"; } ?>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['behavior_notes']); ?></td>
                    <?php if ($user_type_id != 3) { ?>
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
