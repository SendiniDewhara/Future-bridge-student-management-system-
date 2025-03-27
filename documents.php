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
$batch_name_filter = isset($_GET['batch_name']) ? $_GET['batch_name'] : '';

// SQL query to fetch documents based on filters
$sql = "SELECT sd.document_id, sd.student_id, sd.document_name, sd.document_type, sd.file_path, 
               s.first_name, b.batch_name
        FROM student_documents sd
        INNER JOIN students s ON sd.student_id = s.student_id
        INNER JOIN batches b ON s.batch_id = b.batch_id
        WHERE 1=1";

if ($student_id_filter != '') {
    $sql .= " AND sd.student_id = '$student_id_filter'";
}

if ($batch_name_filter != '') {
    $sql .= " AND b.batch_name = '$batch_name_filter'";
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
                echo "<script>alert('Document uploaded successfully!');</script>";
            } else {
                echo "<script>alert('Error uploading document: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading file.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Documents</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right,rgb(90, 131, 194),rgb(74, 136, 156));
            margin: 0;
            padding: 20px;
            color: #fff;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        h2 {
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Forms */
        form {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
            color: #fff;
        }

        input[type="text"], select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            outline: none;
        }

        input, select {
            background: rgba(255, 255, 255, 0.5);
            color: #000;
        }

        /* Buttons */
        button {
            padding: 12px 20px;
            font-size: 16px;
            color: #fff;
            background:rgb(75, 125, 201);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background:rgb(65, 96, 146);
        }

        /* Table */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
            color: #fff;
        }

        th {
            background: rgba(255, 255, 255, 0.3);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        td a {
            color: #ffcc00;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Filter Form -->
    <h2>Filter Student Documents</h2>
    <form method="GET" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" value="<?= $student_id_filter ?>">

        <label for="batch_name">Batch Name:</label>
        <input type="text" name="batch_name" id="batch_name" value="<?= $batch_name_filter ?>">

        <button type="submit">Filter</button>
    </form>

    <!-- Upload Document Form -->
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

    <!-- Documents Table -->
    <h2>Student Documents</h2>
    <table>
        <thead>
            <tr>
                <th>Document ID</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Batch Name</th>
                <th>Document Name</th>
                <th>Document Type</th>
                <th>File</th>
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
                    echo "<td>{$row['batch_name']}</td>";
                    echo "<td>{$row['document_name']}</td>";
                    echo "<td>{$row['document_type']}</td>";
                    echo "<td><a href='{$row['file_path']}' target='_blank'>View</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No documents found</td></tr>";
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
