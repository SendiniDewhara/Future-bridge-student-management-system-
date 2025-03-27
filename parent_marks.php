<?php
// Database Connection
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'student_management_system';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch marks from the database
try {
    $query = "SELECT mark_id, student_id, term, subject, marks FROM term_marks ORDER BY term ASC, student_id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $marks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marks</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff; /* Light blue background */
            font-family: 'Arial', sans-serif;
            color: #333;
            padding-top: 50px;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Transparent white background */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 900px;
        }
        h2 {
            color:rgb(60, 99, 139); /* Blue color for heading */
            font-size: 32px;
            margin-bottom: 30px;
        }
        .btn-success {
            background-color:rgb(75, 114, 199); /* Blue button */
            border-color: #0056b3;
        }
        .btn-success:hover {
            background-color: #0056b3;
            border-color:rgb(60, 106, 155);
        }
        table {
            margin-top: 20px;
        }
        th {
            background-color:rgb(61, 119, 182); /* Blue background for table header */
            color: white;
        }
        td {
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center">Student Marks</h2>
        
        <!-- Export Button -->
        <a href="export_marks.php" class="btn btn-success">Download as Excel</a> <br><br>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mark ID</th>
                    <th>Student ID</th>
                    <th>Term</th>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($marks)) {
                    foreach ($marks as $row) {
                        echo "<tr>
                                <td>{$row['mark_id']}</td>
                                <td>{$row['student_id']}</td>
                                <td>Term {$row['term']}</td>
                                <td>{$row['subject']}</td>
                                <td>{$row['marks']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No marks uploaded yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
