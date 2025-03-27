<?php
// Include the database connection file
include('db_connection.php');

// Fetch all homework assignments from the database
try {
    $sql = "SELECT h.homework_id, h.student_id, h.subject, h.assignment_description, h.due_date, h.status, h.file_paths, b.batch_name
            FROM homework h
            JOIN batches b ON h.batch_id = b.batch_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $homework_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching homework: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Homework Submissions</title>
    <style>
        /* Global styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, rgba(45, 95, 151, 0.95), rgba(65, 166, 224, 0.89));
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Container for content */
        .container {
            width: 85%;
            margin: 30px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            color:rgb(48, 78, 110);
            margin-bottom: 20px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        th {
            background-color:rgb(49, 86, 126);
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
        }

        /* Status labels */
        .status {
            font-weight: bold;
        }

        .status.pending {
            color: orange;
        }

        .status.completed {
            color: green;
        }

        .status.rejected {
            color: red;
        }

        /* Button styles */
        .view-btn, .download-btn {
            padding: 8px 16px;
            background-color:rgb(60, 102, 146);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover, .download-btn:hover {
            background-color:rgb(82, 132, 185);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            th, td {
                padding: 8px;
            }

            h2 {
                font-size: 1.5rem;
            }
        }

    </style>
</head>
<body>

    <div class="container">
        <h2>Manage Homework Submissions</h2>

        <table>
            <thead>
                <tr>
                    <th>Batch</th>
                    <th>Student ID</th>
                    <th>Subject</th>
                    <th>Assignment Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Files</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($homework_list as $homework): ?>
                    <tr>
                        <td><?= $homework['batch_name'] ?></td>
                        <td><?= $homework['student_id'] ?></td>
                        <td><?= $homework['subject'] ?></td>
                        <td><?= $homework['assignment_description'] ?></td>
                        <td><?= $homework['due_date'] ?></td>
                        <td class="status <?= strtolower($homework['status']) ?>"><?= ucfirst($homework['status']) ?></td>
                        <td>
                            <?php if ($homework['file_paths']): ?>
                                <a href="<?= $homework['file_paths'] ?>" class="download-btn" download>Download Files</a>
                            <?php else: ?>
                                No Files
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
