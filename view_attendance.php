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

// Fetch attendance records based on batch and date filter
$query = "SELECT s.student_id, s.first_name, s.last_name, s.batch_id, a.status 
          FROM students s 
          INNER JOIN attendance a ON s.student_id = a.student_id 
          WHERE a.date = :date";

$params = [':date' => $date];

if ($batch_id !== 'all') {
    $query .= " AND s.batch_id = :batch_id";
    $params[':batch_id'] = $batch_id;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #FFFFFF; /* White background */
            color: #333; /* Dark text for readability */
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            padding: 40px 0;
            font-size: 2.5em;
            font-weight: 700;
            text-transform: uppercase;
            color: #3498db; /* Blue color for the header */
            background: rgba(255, 255, 255, 0.7); /* Transparent white background */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        form {
            text-align: center;
            margin: 40px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.7); /* Transparent white background */
            border-radius: 15px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        select, input[type="date"], button {
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            border: 2px solid #3498db; /* Blue border */
            background: rgba(255, 255, 255, 0.7); /* Transparent white background */
            color: #3498db; /* Blue text */
            font-size: 1.1em;
        }

        button {
            background-color: #3498db; /* Blue button */
            color: white;
            border: none;
            cursor: pointer;
            padding: 15px 30px;
            font-size: 1.2em;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #2980b9; /* Darker blue on hover */
            transform: scale(1.05); /* Slight hover effect */
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.8); /* Transparent white background */
            border-radius: 10px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        th {
            background-color: #3498db; /* Blue header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: rgba(240, 240, 240, 0.5);
        }

        tr:hover {
            background-color: rgba(240, 240, 240, 0.8);
            cursor: pointer;
        }

        .no-records {
            text-align: center;
            font-size: 1.5em;
            color: #e74c3c; /* Red for error message */
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>View Attendance</h2>

    <form method="GET">
        <label for="batch_id">Select Batch:</label>
        <select name="batch_id" id="batch_id">
            <option value="all" <?= ($batch_id == 'all') ? 'selected' : '' ?>>All Batches</option>
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

    <?php if (!empty($attendances)): ?>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Batch</th>
                    <th>Attendance Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendances as $attendance): ?>
                    <tr>
                        <td><?= htmlspecialchars($attendance['first_name'] . ' ' . $attendance['last_name']) ?></td>
                        <td><?= !empty($attendance['batch_id']) ? htmlspecialchars($attendance['batch_id']) : 'No Batch' ?></td>
                        <td><?= htmlspecialchars($attendance['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-records">No attendance records found for this selection.</p>
    <?php endif; ?>
    
</body>
</html>
