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
$batch_filter = isset($_GET['batch']) ? $_GET['batch'] : '';

// SQL query to fetch government exam results based on filter, including first_name, batch from students table
$sql = "SELECT ger.result_id, ger.student_id, s.first_name, s.batch, ger.exam_name, ger.result, ger.result_image
        FROM government_exam_results ger
        INNER JOIN students s ON ger.student_id = s.student_id
        WHERE 1=1";

// Apply batch filter if provided
if ($batch_filter != '') {
    $sql .= " AND s.batch = '$batch_filter'";
}

$result = $conn->query($sql);

// Handle form submission for adding exam results
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_result'])) {
    $student_id = $_POST['student_id'];
    $exam_name = $_POST['exam_name'];
    $result = $_POST['result'];

    // Handle file upload for result sheet image
    if (isset($_FILES['result_image']) && $_FILES['result_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["result_image"]["name"]);
        
        // Check if the file is a valid image
        $check = getimagesize($_FILES["result_image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["result_image"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["result_image"]["name"])) . " has been uploaded.";
                
                // Insert the result data into the database
                $insert_sql = "INSERT INTO government_exam_results (student_id, exam_name, result, result_image) 
                               VALUES ('$student_id', '$exam_name', '$result', '$target_file')";
                
                if ($conn->query($insert_sql) === TRUE) {
                    echo "New exam result added successfully!";
                } else {
                    echo "Error: " . $insert_sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        echo "No file selected or an error occurred during file upload.";
    }
}

?>

<!-- Front-end HTML and Form for Filtering, Uploading, and Viewing Exam Results -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Exam Results</title>
</head>
<body>

    <h2>Government Exam Results</h2>

    <h3>Filter Exam Results by Batch</h3>
    <form method="GET" action="">
        <label for="batch">Batch:</label>
        <input type="text" name="batch" id="batch" value="<?= $batch_filter ?>">

        <button type="submit">Filter</button>
    </form>

    <h3>Existing Exam Results</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Result ID</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Batch</th>
                <th>Exam Name</th>
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
                    echo "<td>{$row['batch']}</td>";  
                    echo "<td>{$row['exam_name']}</td>";
                    echo "<td>{$row['result']}</td>";
                    // Displaying the uploaded result image
                    if ($row['result_image']) {
                        echo "<td><img src='" . $row['result_image'] . "' alt='Result Image' width='100'></td>";
                    } else {
                        echo "<td>No Image</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No exam results found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>

    <h3>Add New Exam Result</h3>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" required>

        <label for="exam_name">Exam Name:</label>
        <input type="text" name="exam_name" id="exam_name" required>

        <label for="result">Result:</label>
        <textarea name="result" id="result" required></textarea>

        <label for="result_image">Result Sheet Image:</label>
        <input type="file" name="result_image" id="result_image" accept="image/*">

        <button type="submit" name="submit_result">Add Result</button>
    </form>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
