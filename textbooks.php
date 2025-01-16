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

// SQL query to fetch textbook distribution based on filter, including first_name and batch from students table
$sql = "SELECT t.textbook_id, t.student_id, s.first_name, s.batch, t.textbook_name, t.received, t.distribution_date
        FROM textbooks t
        INNER JOIN students s ON t.student_id = s.student_id
        WHERE 1=1";

// Apply filters
if ($student_id_filter != '') {
    $sql .= " AND t.student_id = '$student_id_filter'";
}

if ($batch_filter != '') {
    $sql .= " AND s.batch = '$batch_filter'";
}

$result = $conn->query($sql);

// Handle form submission for adding textbook distribution
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $textbook_name = $_POST['textbook_name'];
    $received = $_POST['received'] ? 1 : 0;
    $distribution_date = $_POST['distribution_date'];

    $insert_sql = "INSERT INTO textbooks (student_id, textbook_name, received, distribution_date) 
                   VALUES ('$student_id', '$textbook_name', '$received', '$distribution_date')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "New textbook distribution record added successfully!";
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
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
</head>
<body>

    <h2>Textbook Distribution</h2>

    <h3>Filter Textbook Distribution by Student ID and Batch</h3>
    <form method="GET" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" value="<?= $student_id_filter ?>">

        <label for="batch">Batch:</label>
        <input type="text" name="batch" id="batch" value="<?= $batch_filter ?>">

        <button type="submit">Filter</button>
    </form>

    <h3>Existing Textbook Distributions</h3>
    <table border="1">
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
                    echo "<td>{$row['batch']}</td>";  
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

    <br>

    <h3>Add New Textbook Distribution</h3>
    <form method="POST" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" required>

        <label for="textbook_name">Textbook Name:</label>
        <input type="text" name="textbook_name" id="textbook_name" required>

        <label for="received">Received:</label>
        <select name="received" id="received">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <label for="distribution_date">Distribution Date:</label>
        <input type="date" name="distribution_date" id="distribution_date" required>

        <button type="submit">Add Distribution</button>
    </form>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
