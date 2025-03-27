<?php
session_start();

// Check if the user is logged in as a parent
if (!isset($_SESSION['user_id']) || $_SESSION['user_type_id'] != 3) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "student_management_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in parent's user ID and linked student details
$user_id = $_SESSION['user_id'];
$query = "
    SELECT u.user_id, u.username, u.user_type_id, u.student_id, s.first_name, s.last_name, s.dob 
    FROM users u
    LEFT JOIN students s ON u.student_id = s.student_id
    WHERE u.user_id = ?
";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Check if the parent has a linked student
if (!$user_data['student_id']) {
    echo "No student linked to your account.";
    exit();
}

// Fetch term marks
$marks_query = "SELECT term, subject, marks FROM term_marks WHERE student_id = ?";
$stmt = $conn->prepare($marks_query);
$stmt->bind_param("i", $user_data['student_id']);
$stmt->execute();
$marks_result = $stmt->get_result();
$stmt->close();

// Fetch extracurricular activities
$activities_query = "SELECT activity_name, participation_level FROM extracurricular_activities WHERE student_id = ?";
$stmt = $conn->prepare($activities_query);
$stmt->bind_param("i", $user_data['student_id']);
$stmt->execute();
$activities_result = $stmt->get_result();
$stmt->close();

// Fetch attendance records
$attendance_query = "SELECT date, status FROM attendance WHERE student_id = ?";
$stmt = $conn->prepare($attendance_query);
$stmt->bind_param("i", $user_data['student_id']);
$stmt->execute();
$attendance_result = $stmt->get_result();
$stmt->close();

// Fetch behavior details
$behavior_query = "SELECT behavior_notes FROM student_behavior WHERE student_id = ?";
$stmt = $conn->prepare($behavior_query);
$stmt->bind_param("i", $user_data['student_id']);
$stmt->execute();
$behavior_result = $stmt->get_result();
$stmt->close();

// Fetch mental health details separately
function fetchMentalHealth($conn, $student_id) {
    $mental_health_query = "SELECT mental_health_status, observation_date FROM mental_health WHERE student_id = ?";
    $stmt = $conn->prepare($mental_health_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

$mental_health_result = fetchMentalHealth($conn, $user_data['student_id']);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child's Performance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Child's Performance</h1>
        <p><strong>Student Name:</strong> <?= htmlspecialchars($user_data['first_name'] . " " . $user_data['last_name']); ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user_data['dob']); ?></p>
        <p><strong>Student ID:</strong> <?= htmlspecialchars($user_data['student_id']); ?></p>


        <h2>Term Marks</h2>
        <table>
            <tr>
                <th>Term</th>
                <th>Subject</th>
                <th>Marks</th>
            </tr>
            <?php while ($row = $marks_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['term']); ?></td>
                    <td><?= htmlspecialchars($row['subject']); ?></td>
                    <td><?= htmlspecialchars($row['marks']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Extracurricular Activities</h2>
        <table>
            <tr>
                <th>Activity</th>
                <th>Participation Level</th>
            </tr>
            <?php while ($row = $activities_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['activity_name']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['participation_level'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Attendance Records</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $attendance_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars(date('d M Y', strtotime($row['date']))); ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['status'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Behavior Records</h2>
        <?php if ($behavior_row = $behavior_result->fetch_assoc()): ?>
            <p><strong>Behavior Notes:</strong> <?= htmlspecialchars($behavior_row['behavior_notes']); ?></p>
        <?php else: ?>
            <p>No behavior records available.</p>
        <?php endif; ?>

        <h2>Mental Health Status</h2>
        <table>
            <tr>
                <th>Observation Date</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $mental_health_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars(date('d M Y', strtotime($row['observation_date']))); ?></td>
                    <td><?= htmlspecialchars($row['mental_health_status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
