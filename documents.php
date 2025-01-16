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

// SQL query to fetch documents based on filters
$sql = "SELECT sd.document_id, sd.student_id, sd.document_name, sd.document_type, sd.file_path, s.first_name, s.batch
        FROM student_documents sd
        INNER JOIN students s ON sd.student_id = s.student_id
        WHERE 1=1";

if ($student_id_filter != '') {
    $sql .= " AND sd.student_id = '$student_id_filter'";
}

if ($batch_filter != '') {
    $sql .= " AND s.batch = '$batch_filter'";
}

$result = $conn->query($sql);

// Handling file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['document_file']) && isset($_POST['student_id']) && isset($_POST['document_name']) && isset($_POST['document_type'])) {
        $student_id = $_POST['student_id'];
        $document_name = $_POST['document_name'];
        $document_type = $_POST['document_type'];
        $file_path = 'uploads/' . basename($_FILES['document_file']['name']);

        // Move the uploaded file to the server directory
        if (move_uploaded_file($_FILES['document_file']['tmp_name'], $file_path)) {
            $insert_sql = "INSERT INTO student_documents (student_id, document_name, document_type, file_path) 
                           VALUES ('$student_id', '$document_name', '$document_type', '$file_path')";
            if ($conn->query($insert_sql) === TRUE) {
                echo "Document uploaded successfully!";
            } else {
                echo "Error uploading document: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    }
}

?>

<!-- Front-end HTML and Filter Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Documents</title>
</head>
<body>

<h2>Filter Student Documents</h2>
<form method="GET" action="">
    <label for="student_id">Student ID:</label>
    <input type="text" name="student_id" id="student_id" value="<?= $student_id_filter ?>">

    <label for="batch">Batch:</label>
    <input type="text" name="batch" id="batch" value="<?= $batch_filter ?>">

    <button type="submit">Filter</button>
</form>

<h2>Upload Student Document</h2>
<form method="POST" enctype="multipart/form-data">
    <label for="student_id">Student ID:</label>
    <input type="text" name="student_id" required>

    <label for="document_name">Document Name:</label>
    <input type="text" name="document_name" required>

    <label for="document_type">Document Type:</label>
    <select name="document_type" required>
        <option value="admission">Admission</option>
        <option value="leaving_certificate">Leaving Certificate</option>
        <option value="report_card">Report Card</option>
        <option value="other">Other</option>
    </select>

    <label for="document_file">File:</label>
    <input type="file" name="document_file" required>

    <button type="submit">Upload Document</button>
</form>

<h2>Student Documents</h2>
<table border="1">
    <thead>
        <tr>
            <th>Document ID</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Batch</th>
            <th>Document Name</th>
            <th>Document Type</th>
            <th>File Path</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['document_id']}</td>";
                echo "<td>{$row['student_id']}</td>";
                echo "<td>{$row['first_name']}</td>";
                echo "<td>{$row['batch']}</td>";
                echo "<td>{$row['document_name']}</td>";
                echo "<td>{$row['document_type']}</td>";
                echo "<td><a href='{$row['file_path']}' target='_blank'>View File</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No documents found</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
