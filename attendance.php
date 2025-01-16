<?php
require 'db_connection.php'; // Include your database connection file

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['register_image'])) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['register_image']['name']);

    if (move_uploaded_file($_FILES['register_image']['tmp_name'], $uploadFile)) {
        // Save file details in the database
        $date = $_POST['date'];
        $class = $_POST['class'];

        $stmt = $pdo->prepare("INSERT INTO student_documents (student_id, document_name, document_type, file_path) VALUES (0, ?, 'register_image', ?)");
        if ($stmt->execute(["Class Register - $class - $date", $uploadFile])) {
            echo "<p class='alert alert-success'>File uploaded successfully!</p>";
        } else {
            echo "<p class='alert alert-danger'>Failed to save file details in the database.</p>";
        }
    } else {
        echo "<p class='alert alert-danger'>Failed to upload the file.</p>";
    }
}

// Fetch uploaded images
$imagesQuery = $pdo->query("SELECT * FROM student_documents WHERE document_type = 'register_image'");
$images = $imagesQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch attendance data for graph
$attendanceQuery = $pdo->query("SELECT date, status, COUNT(*) as count FROM attendance GROUP BY date, status");
$attendanceData = $attendanceQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Attendance Management</h1>

    <!-- Form for uploading register images -->
    <form action="attendance.php" method="POST" enctype="multipart/form-data">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br>

        <label for="class">Class:</label>
        <input type="text" id="class" name="class" required><br>

        <label for="register_image">Upload Register Image:</label>
        <input type="file" id="register_image" name="register_image" accept="image/*" required><br>

        <button type="submit">Upload</button>
    </form>

    <h2>Uploaded Register Images</h2>
    <?php if (!empty($images)): ?>
        <ul>
            <?php foreach ($images as $image): ?>
                <li>
                    <p><strong><?php echo htmlspecialchars($image['document_name']); ?></strong></p>
                    <p>Date: <?php echo htmlspecialchars($image['file_path']); ?></p>
                    <img src="<?php echo htmlspecialchars($image['file_path']); ?>" alt="Register Image" style="max-width: 200px;">
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No images uploaded yet.</p>
    <?php endif; ?>

    <h2>Attendance Graph</h2>
    <canvas id="attendanceChart" width="400" height="200"></canvas>

    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceData = <?php echo json_encode($attendanceData); ?>;

        const labels = [...new Set(attendanceData.map(item => item.date))];
        const presentData = labels.map(date => {
            const record = attendanceData.find(item => item.date === date && item.status === 'present');
            return record ? record.count : 0;
        });
        const absentData = labels.map(date => {
            const record = attendanceData.find(item => item.date === date && item.status === 'absent');
            return record ? record.count : 0;
        });
        const lateData = labels.map(date => {
            const record = attendanceData.find(item => item.date === date && item.status === 'late');
            return record ? record.count : 0;
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Present',
                        data: presentData,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Absent',
                        data: absentData,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Late',
                        data: lateData,
                        backgroundColor: 'rgba(255, 206, 86, 0.5)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>
</body>
</html>
