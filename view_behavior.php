<?php
// Include the database connection file
include 'db_connection.php';

// Check if the status key exists to avoid warnings
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch all behavior records
try {
    $sql = "SELECT * FROM student_behavior";
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
    <title>View Behavior Records</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, rgba(101, 101, 180, 0.7), rgba(61, 122, 163, 0.87));
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: white;
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 12px;
            text-align: left;
            color: white;
        }
        th {
            background: rgba(69, 69, 175, 0.8);
            color: white;
        }
        .status {
            font-weight: bold;
            color:rgb(226, 225, 221);
        }
        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }
        tr:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>View Behavior Records</h1>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Behavior Notes</th>
                <th>Status</th>
            </tr>
            <?php foreach ($records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($record['behavior_notes']); ?></td>
                    <td class="status">
                        <?php echo isset($record['status']) ? htmlspecialchars($record['status']) : 'Updated Student Account'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
