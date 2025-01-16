<?php
require 'vendor/autoload.php';  // Include Composer's autoload file
use PhpOffice\PhpSpreadsheet\IOFactory;

$host = 'localhost';  // MySQL server host (typically localhost for local servers like XAMPP)
$username = 'root';   // MySQL username (default for XAMPP)
$password = '';       // MySQL password (default is empty for XAMPP)
$db_name = 'student_management_system';  // The name of your database

try {
    // Create a PDO instance with the provided connection details
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, show the error message and stop script execution
    echo "Connection failed: " . $e->getMessage();
    exit;  // Stop further execution if the connection is not established
}

// Handle file upload
if (isset($_POST['submit'])) {
    // Check if a file is uploaded
    if ($_FILES['file']['error'] == 0) {
        // Get the file and batch information
        $file = $_FILES['file']['tmp_name'];
        $batch = $_POST['batch'];  // Batch selection from the dropdown
        $fileName = $_FILES['file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Check if the file is an Excel file
        if ($fileExtension == 'xlsx' || $fileExtension == 'xls') {
            // Handle Excel file
            try {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = [];

                foreach ($sheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    $cells = [];
                    foreach ($cellIterator as $cell) {
                        $cells[] = $cell->getFormattedValue();
                    }
                    $rows[] = $cells;
                }

                // Store timetable data in the database
                foreach ($rows as $row) {
                    $student_id = $row[0];

                    // Check if student_id exists in the students table
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_id = ?");
                    $stmt->execute([$student_id]);
                    $studentExists = $stmt->fetchColumn();

                    if ($studentExists) {
                        // If student_id exists, insert the timetable data including the batch
                        $stmt = $pdo->prepare("INSERT INTO timetable (student_id, day_of_week, start_time, end_time, subject, batch) 
                                              VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$row[0], $row[1], $row[2], $row[3], $row[4], $batch]);
                    } else {
                        echo "Student ID " . htmlspecialchars($student_id) . " does not exist. Skipping this entry.<br>";
                    }
                }

                echo "Timetable uploaded and data saved successfully!";
            } catch (Exception $e) {
                echo "Error reading Excel file: " . $e->getMessage();
            }
        } else {
            echo "Invalid file type. Only Excel files are allowed.";
        }
    } else {
        echo "Error uploading file.";
    }
}

// Fetch timetable data
$timetableQuery = $pdo->query("SELECT timetable_id, student_id, day_of_week, start_time, end_time, subject, batch FROM timetable");
$timetableData = $timetableQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Timetable</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Upload Timetable</h1>

    <!-- Upload Timetable Form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Upload Timetable (Excel file)</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="batch">Select Batch</label>
            <select name="batch" class="form-control" required>
                <option value="">Select Batch</option>
                <option value="Batch A">Batch A</option>
                <option value="Batch B">Batch B</option>
                <option value="Batch C">Batch C</option>
                <!-- Add more batches as needed -->
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
    </form>

    <h2>Existing Timetable Entries</h2>

    <!-- Timetable Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Day of Week</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Subject</th>
                <th>Batch</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timetableData as $entry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($entry['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($entry['day_of_week']); ?></td>
                    <td><?php echo htmlspecialchars($entry['start_time']); ?></td>
                    <td><?php echo htmlspecialchars($entry['end_time']); ?></td>
                    <td><?php echo htmlspecialchars($entry['subject']); ?></td>
                    <td><?php echo htmlspecialchars($entry['batch']); ?></td>
                    <td>
                        <!-- Edit and Delete Actions -->
                        <a href="editTimetable.php?timetable_id=<?php echo $entry['timetable_id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="deleteTimetable.php?timetable_id=<?php echo $entry['timetable_id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
