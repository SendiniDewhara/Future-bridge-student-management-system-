<?php
// Start the session to identify the logged-in user
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and if the user is a parent
if (isset($_SESSION['user_id'])) {
    $parent_id = $_SESSION['user_id'];  // Assuming the user is logged in

    // Fetch the student_id(s) associated with the logged-in parent
    $sql = "SELECT student_id FROM users WHERE user_id = '$parent_id' AND user_type_id = 3"; // 3 is the user_type_id for parents
    $result = $conn->query($sql);
    $student_ids = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $student_ids[] = $row['student_id']; // Collecting all student_ids linked to the parent
        }
    } else {
        echo "No students linked to this parent.";
        exit();
    }

    // Fetch the government exam results for the students linked to the parent
    $student_ids_placeholder = implode(',', $student_ids); // Prepare the list of student IDs for SQL query

    $sql = "SELECT ger.exam_name, ger.exam_type, ger.result, ger.result_image, s.first_name, s.last_name
            FROM government_exam_results ger
            INNER JOIN students s ON ger.student_id = s.student_id
            WHERE ger.student_id IN ($student_ids_placeholder)"; // Fetch exam results for the parent's children

    $result = $conn->query($sql);
} else {
    echo "You must be logged in to view this page.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent - View Government Exam Results</title>
    <style>
        /* Add styles for the parent view */
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
            width: 80%;
            max-width: 1000px;
            text-align: center;
        }

        h2 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
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

        .download-btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .download-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Your Children's Government Exam Results</h2>

        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Exam Name</th>
                    <th>Exam Type</th>
                    <th>Result</th>
                    <th>Result Image</th>
                    <th>Download Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['first_name']} {$row['last_name']}</td>";
                        echo "<td>{$row['exam_name']}</td>";
                        echo "<td>{$row['exam_type']}</td>";
                        echo "<td>{$row['result']}</td>";
                        echo "<td><img src='{$row['result_image']}' alt='Result Image' style='width: 100px; height: auto;'></td>";
                        // Provide a link to download the result image
                        echo "<td><a href='{$row['result_image']}' class='download-btn' download>Download</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No exam results available for your children.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a class="link" href="parent_dashboard.php">Back to Dashboard</a>
    </div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
