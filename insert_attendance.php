<?php
include 'db_connection.php';

// Fetch available batches
try {
    $batchQuery = $pdo->query("SELECT batch_id, batch_name FROM batches ORDER BY batch_id ASC");
    $batches = $batchQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching batches: " . $e->getMessage());
}

// Get filter values
$batch_id = $_GET['batch_id'] ?? 'all';
$date = $_GET['date'] ?? date('Y-m-d');

// Fetch students based on batch and date filter
$query = "SELECT s.student_id, s.first_name, s.last_name, s.batch_id, a.status 
          FROM students s 
          LEFT JOIN attendance a ON s.student_id = a.student_id AND a.date = :date";

$params = [':date' => $date];

if ($batch_id !== 'all') {
    $query .= " WHERE s.batch_id = :batch_id";
    $params[':batch_id'] = $batch_id;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch_id = $_POST['batch_id'];
    $date = $_POST['date'];

    foreach ($_POST['attendance'] as $student_id => $status) {
        // Check if the attendance record exists for the given student and date
        $checkQuery = "SELECT * FROM attendance WHERE student_id = ? AND date = ?";
        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute([$student_id, $date]);

        if ($stmt->rowCount() > 0) {
            // If the record exists, update the attendance
            $updateQuery = "UPDATE attendance SET status = ? WHERE student_id = ? AND date = ?";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([$status, $student_id, $date]);
        } else {
            // If the record does not exist, insert a new attendance record
            $insertQuery = "INSERT INTO attendance (student_id, batch_id, date, status) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->execute([$student_id, ($batch_id === 'all' ? NULL : $batch_id), $date, $status]);
        }
    }

    echo "<script>alert('Attendance marked successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e3d58; /* Dark blue background */
            color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.2); /* Transparent white background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            color: white;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        label {
            display: inline-block;
            margin: 10px 0 5px;
            color: #f0f0f0;
            font-weight: bold;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.3); /* Transparent background */
            color: white;
        }

        select:focus, input[type="date"]:focus {
            outline: none;
            border-color: #1e90ff;
        }

        button {
            padding: 12px 25px;
            font-size: 1rem;
            background-color: rgba(30, 144, 255, 0.8); /* Transparent blue */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: rgba(30, 144, 255, 1); /* Solid blue on hover */
        }

        button:disabled {
            background-color: #ccc;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: rgba(0, 0, 0, 0.1);
        }

        td.radio-group input {
            margin-right: 10px;
        }

        .no-records {
            text-align: center;
            color: #ff0000;
            font-weight: bold;
        }

        /* Hover effect */
        tr:hover {
            background-color: rgba(0, 0, 255, 0.2);
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Mark Attendance</h2>

        <form method="GET">
            <label for="batch_id">Select Batch:</label>
            <select name="batch_id" id="batch_id">
                <option value="all" <?= ($batch_id == 'all') ? 'selected' : '' ?>>All Students</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?= htmlspecialchars($batch['batch_id']) ?>" <?= ($batch_id == $batch['batch_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($batch['batch_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date">Select Date:</label>
            <input type="date" name="date" id="date" value="<?= htmlspecialchars($date) ?>">

            <button type="submit">Filter</button>
        </form>

        <?php if (!empty($students)): ?>
            <form method="POST" id="attendance-form">
                <input type="hidden" name="batch_id" value="<?= htmlspecialchars($batch_id) ?>">
                <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">

                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Batch</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                <td><?= !empty($student['batch_id']) ? htmlspecialchars($student['batch_id']) : 'No Batch' ?></td>
                                <td class="radio-group">
                                    <label>
                                        <input type="radio" name="attendance[<?= $student['student_id'] ?>]" value="Present" <?= ($student['status'] == 'Present') ? 'checked' : '' ?> required> Present
                                    </label>
                                    <label>
                                        <input type="radio" name="attendance[<?= $student['student_id'] ?>]" value="Absent" <?= ($student['status'] == 'Absent') ? 'checked' : '' ?> required> Absent
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" id="submit-button" class="disabled" disabled>Submit Attendance</button>
            </form>
        <?php else: ?>
            <p class="no-records">No students found for this selection.</p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('attendance-form');
            const submitButton = document.getElementById('submit-button');

            function checkAllSelected() {
                const radios = form.querySelectorAll('input[type="radio"]:checked');
                const totalStudents = form.querySelectorAll('input[type="radio"]').length / 2;
                if (radios.length === totalStudents) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }

            form.addEventListener('change', checkAllSelected);
        });
    </script>
</body>
</html>
