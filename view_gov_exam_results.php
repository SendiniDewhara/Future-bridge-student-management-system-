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

// Fetch filter values
$batch_id_filter = isset($_GET['batch_id']) ? $_GET['batch_id'] : '';
$exam_type_filter = isset($_GET['exam_type']) ? $_GET['exam_type'] : '';
$student_id_filter = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Fetch batch names for dropdown
$batch_query = "SELECT batch_id, batch_name FROM batches";
$batch_result = $conn->query($batch_query);

// Fetch student IDs for dropdown
$student_query = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS student_name FROM students";
$student_result = $conn->query($student_query);

// Fetch exam results with student details
$sql = "SELECT ger.result_id, ger.student_id, s.first_name, s.batch_id, b.batch_name, ger.exam_name, ger.exam_type, ger.result, ger.result_image
        FROM government_exam_results ger
        INNER JOIN students s ON ger.student_id = s.student_id
        INNER JOIN batches b ON s.batch_id = b.batch_id
        WHERE 1=1";

// Apply filters to the query
if (!empty($batch_id_filter)) {
    $sql .= " AND s.batch_id = '$batch_id_filter'"; // Filter by batch_id
}

if (!empty($exam_type_filter)) {
    $sql .= " AND ger.exam_type = '$exam_type_filter'"; // Filter by exam_type
}

if (!empty($student_id_filter)) {
    $sql .= " AND ger.student_id = '$student_id_filter'"; // Filter by student_id
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Government Exam Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, rgba(0, 123, 255, 0.5), rgba(72, 150, 214, 0.7));
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            width: 80%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color:rgb(101, 141, 184);
        }

        form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
        }

        select, button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #007bff;
            font-size: 16px;
        }

        button {
            background-color:rgb(76, 124, 175);
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color:rgb(78, 130, 185);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color:rgb(96, 141, 190);
            color: white;
        }

        tr:nth-child(even) {
            background-color: rgba(0, 123, 255, 0.1);
        }

        img {
            width: 80px;
            height: auto;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            form {
                flex-direction: column;
            }
            select, button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Government Exam Results</h2>

        <!-- Filter Form -->
        <form method="GET">
            <label for="batch_id">Batch:</label>
            <select name="batch_id">
                <option value="">All</option>
                <?php
                if ($batch_result->num_rows > 0) {
                    while ($row = $batch_result->fetch_assoc()) {
                        $selected = ($batch_id_filter == $row['batch_id']) ? "selected" : "";
                        echo "<option value='{$row['batch_id']}' {$selected}>{$row['batch_name']}</option>";
                    }
                }
                ?>
            </select>

            <label for="exam_type">Exam Type:</label>
            <select name="exam_type">
                <option value="">All</option>
                <option value="Scholarship" <?php if ($exam_type_filter == "Scholarship") echo "selected"; ?>>Scholarship</option>
                <option value="OL" <?php if ($exam_type_filter == "OL") echo "selected"; ?>>O/L</option>
                <option value="AL" <?php if ($exam_type_filter == "AL") echo "selected"; ?>>A/L</option>
            </select>

            <label for="student_id">Student:</label>
            <select name="student_id">
                <option value="">All</option>
                <?php
                if ($student_result->num_rows > 0) {
                    while ($row = $student_result->fetch_assoc()) {
                        $selected = ($student_id_filter == $row['student_id']) ? "selected" : "";
                        echo "<option value='{$row['student_id']}' {$selected}>{$row['student_name']}</option>";
                    }
                }
                ?>
            </select>

            <button type="submit">Apply Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Result ID</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Batch</th>
                    <th>Exam Name</th>
                    <th>Exam Type</th>
                    <th>Result</th>
                    <th>Result Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['result_id']}</td>";
                        echo "<td>{$row['student_id']}</td>";
                        echo "<td>{$row['first_name']}</td>";
                        echo "<td>{$row['batch_name']}</td>";
                        echo "<td>{$row['exam_name']}</td>";
                        echo "<td>{$row['exam_type']}</td>";
                        echo "<td>{$row['result']}</td>";
                        if (!empty($row['result_image'])) {
                            echo "<td><img src='" . htmlspecialchars($row['result_image']) . "' alt='Result Image'></td>";
                        } else {
                            echo "<td>No Image</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No exam results found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
