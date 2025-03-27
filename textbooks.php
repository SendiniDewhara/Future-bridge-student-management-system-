<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default filter values
$student_id_filter = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$batch_filter = isset($_GET['batch']) ? $_GET['batch'] : '';

// SQL query to fetch textbook distribution based on filter, including first_name and batch_name from batches table
$sql = "SELECT t.textbook_id, t.student_id, s.first_name, b.batch_name, t.textbook_name, t.received, t.distribution_date
        FROM textbooks t
        INNER JOIN students s ON t.student_id = s.student_id
        INNER JOIN batches b ON s.batch_id = b.batch_id
        WHERE 1=1";

// Apply filters
if ($student_id_filter != '') {
    $sql .= " AND t.student_id = '$student_id_filter'";
}

if ($batch_filter != '') {
    $sql .= " AND b.batch_name = '$batch_filter'";
}

$result = $conn->query($sql);

// Handle form submission for adding textbook distribution
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $textbook_name = $_POST['textbook_name'];
    $received = $_POST['received'] ? 1 : 0;
    $distribution_date = $_POST['distribution_date'];

    // Check if the student ID exists in the students table
    $check_student_sql = "SELECT student_id FROM students WHERE student_id = '$student_id'";
    $check_student_result = $conn->query($check_student_sql);

    if ($check_student_result->num_rows > 0) {
        // Student ID exists, proceed with the insert query
        $insert_sql = "INSERT INTO textbooks (student_id, textbook_name, received, distribution_date) 
                       VALUES ('$student_id', '$textbook_name', '$received', '$distribution_date')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "<div class='alert success'>New textbook distribution record added successfully!</div>";
        } else {
            echo "<div class='alert error'>Error: " . $insert_sql . "<br>" . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert error'>Error: Student ID does not exist!</div>";
    }
}
?>

<!-- Front-end HTML and Form for Filtering and Adding Textbook Distribution -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Textbook Distribution</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(77, 146, 206);
            margin: 0;
            padding: 0;
        }
        header {
            background-color:rgb(65, 134, 207);
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .container h2, .container h3 {
            text-align: center;
            color:rgb(45, 92, 143);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color:rgb(212, 207, 207);
        }
        .form-group button {
            background-color:rgb(47, 106, 168);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f0f8ff;
        }
        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .alert.success {
            background-color: #28a745;
            color: white;
        }
        .alert.error {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

    <header>
        <h1>Student Textbook Distribution System</h1>
    </header>

    <div class="container">
        <h2>Filter Textbook Distribution by Student ID and Batch</h2>
        <form method="GET" action="">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" id="student_id" value="<?= $student_id_filter ?>" placeholder="Enter Student ID">
            </div>

            <div class="form-group">
                <label for="batch">Batch:</label>
                <input type="text" name="batch" id="batch" value="<?= $batch_filter ?>" placeholder="Enter Batch">
            </div>

            <div class="form-group">
                <button type="submit">Filter</button>
            </div>
        </form>

        <h3>Existing Textbook Distributions</h3>
        <table>
            <thead>
                <tr>
                    <th>Textbook ID</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Batch</th>
                    <th>Textbook Name</th>
                    <th>Received</th>
                    <th>Distribution Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['textbook_id']}</td>";
                        echo "<td>{$row['student_id']}</td>";
                        echo "<td>{$row['first_name']}</td>";
                        echo "<td>{$row['batch_name']}</td>";
                        echo "<td>{$row['textbook_name']}</td>";
                        echo "<td>" . ($row['received'] ? 'Yes' : 'No') . "</td>"; // Display Received as Yes/No
                        echo "<td>{$row['distribution_date']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No textbook distribution records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Add New Textbook Distribution</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" id="student_id" required placeholder="Enter Student ID">
            </div>

            <div class="form-group">
                <label for="textbook_name">Textbook Name:</label>
                <input type="text" name="textbook_name" id="textbook_name" required placeholder="Enter Textbook Name">
            </div>

            <div class="form-group">
                <label for="received">Received:</label>
                <select name="received" id="received">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="form-group">
                <label for="distribution_date">Distribution Date:</label>
                <input type="date" name="distribution_date" id="distribution_date" required>
            </div>

            <div class="form-group">
                <button type="submit">Add Distribution</button>
            </div>
        </form>
    </div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
