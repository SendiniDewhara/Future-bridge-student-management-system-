<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Filters
$student_id_filter = $_GET['student_id'] ?? '';
$activity_name_filter = $_GET['activity_name'] ?? '';
$status_filter = $_GET['status'] ?? '';
$participation_level_filter = $_GET['participation_level'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$sql = "SELECT * FROM extracurricular_activities WHERE 1=1";
if ($student_id_filter != '') $sql .= " AND student_id = '$student_id_filter'";
if ($activity_name_filter != '') $sql .= " AND activity_name LIKE '%$activity_name_filter%'";
if ($status_filter != '') $sql .= " AND status = '$status_filter'";
if ($participation_level_filter != '') $sql .= " AND participation_level = '$participation_level_filter'";
if ($start_date != '' && $end_date != '') $sql .= " AND last_participation_date BETWEEN '$start_date' AND '$end_date'";

$result = $conn->query($sql);
$activities = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracurricular Activities</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.3), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(10px);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        .table th {
            background-color: rgba(0, 123, 255, 0.7);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #003d80);
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center text-white">Extracurricular Activities</h2>

    <!-- Filter Form -->
    <div class="glass-card">
        <form method="GET" action="">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?= $student_id_filter ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="activity_name" class="form-control" placeholder="Activity Name" value="<?= $activity_name_filter ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Status</option>
                        <option value="active" <?= $status_filter == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status_filter == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="participation_level" class="form-select">
                        <option value="">Level</option>
                        <option value="beginner" <?= $participation_level_filter == 'beginner' ? 'selected' : '' ?>>Beginner</option>
                        <option value="intermediate" <?= $participation_level_filter == 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                        <option value="advanced" <?= $participation_level_filter == 'advanced' ? 'selected' : '' ?>>Advanced</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Add Activity Button -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h3 class="text-white">Extracurricular Activities</h3>
        <a href="add_extracurricular.php" class="btn btn-success">Add Activity</a>
    </div>

    <!-- Table -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover">
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
            <tbody id="activityTable">
                <?php if (!empty($activities)) : ?>
                    <?php foreach ($activities as $activity) : ?>
                        <tr>
                            <td><?= $activity['activity_id'] ?></td>
                            <td><?= $activity['student_id'] ?></td>
                            <td><?= $activity['activity_name'] ?></td>
                            <td><?= ucfirst($activity['participation_level']) ?></td>
                            <td><?= $activity['last_participation_date'] ?></td>
                            <td class="<?= $activity['status'] == 'active' ? 'text-success' : 'text-danger' ?>">
                                <?= ucfirst($activity['status']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="6" class="text-center text-muted">No activities found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Chart -->
    <h3 class="mt-5 text-center text-white">Participation Trends</h3>
    <canvas id="activityChart" class="mt-3"></canvas>
</div>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let activityData = <?= json_encode($activities) ?>;
    let labels = activityData.map(a => a.activity_name);
    let levels = activityData.map(a => {
        if (a.participation_level === "beginner") return 1;
        if (a.participation_level === "intermediate") return 2;
        if (a.participation_level === "advanced") return 3;
        return 0;
    });

    let ctx = document.getElementById("activityChart").getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Participation Level",
                data: levels,
                backgroundColor: "#007bff",
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
});
</script>

</body>
</html>
