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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $exam_name = mysqli_real_escape_string($conn, $_POST['exam_name']);
    $exam_type = mysqli_real_escape_string($conn, $_POST['exam_type']);
    $result = mysqli_real_escape_string($conn, $_POST['result']);
    
    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["result_image"]["name"]);
    move_uploaded_file($_FILES["result_image"]["tmp_name"], $target_file);

    // Insert data into government_exam_results table
    $sql = "INSERT INTO government_exam_results (student_id, exam_name, exam_type, result, result_image) 
            VALUES ('$student_id', '$exam_name', '$exam_type', '$result', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Result added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Government Exam Result</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(73, 143, 224, 0.8), rgba(85, 161, 211, 0.8)), url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 25px;
            border-radius: 12px;
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 380px;
            text-align: center;
        }

        h2 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 15px;
        }

        label {
            display: block;
            text-align: left;
            color: #ffffff;
            font-size: 14px;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            outline: none;
        }

        button {
            background:rgb(70, 122, 180);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        button:hover {
            background:rgb(93, 130, 173);
            transform: scale(1.05);
        }

        .link {
            display: block;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Add Government Exam Result</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" required>

            <label for="exam_name">Exam Name:</label>
            <input type="text" name="exam_name" required>

            <label for="exam_type">Exam Type:</label>
            <select name="exam_type" required>
                <option value="Scholarship">Scholarship</option>
                <option value="OL">O/L</option>
                <option value="AL">A/L</option>
            </select>

            <label for="result">Result:</label>
            <input type="text" name="result" required>

            <label for="result_image">Upload Result Image:</label>
            <input type="file" name="result_image" required>

            <button type="submit">Add Result</button>
        </form>

        <a class="link" href="view_gov_exam_results.php">View Government Exam Results</a>
    </div>

</body>
</html>
