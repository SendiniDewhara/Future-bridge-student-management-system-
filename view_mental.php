<?php
// Include the database connection file
include 'db_connection.php';

try {
    // Fetch all records from the `mental_health` table
    $sql = "SELECT * FROM mental_health ORDER BY observation_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching records: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Mental Health Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
        }
        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .top-right a {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .top-right a:hover {
            background-color: #0056b3;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h1>Mental Health Records</h1>

    <!-- Link to go back to add record page -->
    <div class="top-right">
        <a href="add_mental_health.php">Add Mental Health Record</a>
    </div>

    <?php if (count($records) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Mental Health Status</th>
                    <th>Observation Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($record['mental_health_status']); ?></td>
                        <td><?php echo htmlspecialchars($record['observation_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No records found.</p>
    <?php endif; ?>

    

</body>
</html>
