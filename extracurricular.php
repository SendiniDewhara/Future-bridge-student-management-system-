<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default filter values
$student_id_filter = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$activity_name_filter = isset($_GET['activity_name']) ? $_GET['activity_name'] : '';
$participation_status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$participation_level_filter = isset($_GET['participation_level']) ? $_GET['participation_level'] : '';

// SQL query to fetch activities based on filters
$sql = "SELECT ea.activity_id, ea.student_id, ea.activity_name, ea.participation_level, ea.last_participation_date, ea.status
        FROM extracurricular_activities ea
        INNER JOIN students s ON ea.student_id = s.student_id
        WHERE 1=1";

if ($student_id_filter != '') {
    $sql .= " AND ea.student_id = '$student_id_filter'";
}

if ($activity_name_filter != '') {
    $sql .= " AND ea.activity_name LIKE '%$activity_name_filter%'";
}

if ($participation_status_filter != '') {
    $sql .= " AND ea.status = '$participation_status_filter'";
}

if ($participation_level_filter != '') {
    $sql .= " AND ea.participation_level = '$participation_level_filter'";
}

$result = $conn->query($sql);

?>

<!-- Front-end HTML and Filter Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracurricular Activities</title>
</head>
<body>

    <h2>Filter Students and Extracurricular Activities</h2>

    <form method="GET" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" value="<?= $student_id_filter ?>">

        <label for="activity_name">Activity Name:</label>
        <input type="text" name="activity_name" id="activity_name" value="<?= $activity_name_filter ?>">

        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="">All</option>
            <option value="active" <?= $participation_status_filter == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $participation_status_filter == 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>

        <label for="participation_level">Participation Level:</label>
        <select name="participation_level" id="participation_level">
            <option value="">All Levels</option>
            <option value="beginner" <?= $participation_level_filter == 'beginner' ? 'selected' : '' ?>>Beginner</option>
            <option value="intermediate" <?= $participation_level_filter == 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
            <option value="advanced" <?= $participation_level_filter == 'advanced' ? 'selected' : '' ?>>Advanced</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>Activity ID</th>
                <th>Student ID</th>
                <th>Activity Name</th>
                <th>Participation Level</th>
                <th>Last Participation Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['activity_id']}</td>";
                    echo "<td>{$row['student_id']}</td>";
                    echo "<td>{$row['activity_name']}</td>";
                    echo "<td>{$row['participation_level']}</td>";
                    echo "<td>{$row['last_participation_date']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No activities found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>

    <h2>Activity Progress Graph</h2>
    <canvas id="activityChart"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Activity 1', 'Activity 2', 'Activity 3'],  // Example labels, dynamically add based on data
                datasets: [{
                    label: 'Progress Over Time',
                    data: [12, 19, 3],  // Example data, replace with actual participation data
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
