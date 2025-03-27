<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system"; // Replace with your actual database name

// Start the session to retrieve session variables
session_start();

// Check if the user is logged in by verifying if the necessary session variables exist
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type_id'])) {
    // Redirect to login page or show an error if the session is not set
    header("Location: login.php"); // Replace with the path to your login page
    exit();
}

// Get logged-in user_id and user_type_id (assuming user is authenticated and session variables are set)
$user_id = $_SESSION['user_id'];
$user_type_id = $_SESSION['user_type_id'];

// Default filter values
$student_id_filter = '';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user's type (user_type_name) from the user_types table using a prepared statement
$query = "SELECT user_type_name FROM user_types WHERE user_type_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_type_id); // bind the user_type_id as integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_type = $row['user_type_name'];
} else {
    echo "Invalid user type.";
    exit();
}

// Fetch the student's id associated with the logged-in parent (for user_type = 'parent')
if ($user_type == 'parent') {
    // Query to get the student's ID for the parent using a prepared statement
    $query = "SELECT student_id FROM users WHERE user_id = ? AND user_type_id = (SELECT user_type_id FROM user_types WHERE user_type_name = 'parent')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id); // bind the user_id as integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_id_filter = $row['student_id'];  // Set student's ID for filtering documents
    } else {
        echo "No student found for this parent.";
        exit();
    }
}

// SQL query to fetch documents for the specific student
$sql = "SELECT sd.document_id, sd.student_id, sd.document_name, sd.document_type, sd.file_path, 
               s.first_name, s.last_name, s.batch_id 
        FROM student_documents sd
        INNER JOIN students s ON sd.student_id = s.student_id
        WHERE sd.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id_filter); // bind the student_id as integer
$stmt->execute();
$result = $stmt->get_result();

// Handle file view if the 'view' query parameter is set
if (isset($_GET['view'])) {
    $file_path = $_GET['view'];

    // Validate if the file exists
    if (file_exists($file_path)) {
        $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);

        // If it's a PDF, use an iframe to display it
        if (strtolower($file_extension) == 'pdf') {
            echo '<h3>Viewing Document: ' . basename($file_path) . '</h3>';
            echo '<iframe src="' . $file_path . '" width="100%" height="600px"></iframe>';
        }
        // If it's an image, use an <img> tag to display it
        elseif (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
            echo '<h3>Viewing Image: ' . basename($file_path) . '</h3>';
            echo '<img src="' . $file_path . '" width="100%" alt="Document Image" />';
        }
        // Add more conditions if needed for other file types
        else {
            echo 'File type not supported for viewing in the browser.';
        }
        
        // Provide a download link below the document
        echo '<br><br><a href="?download=' . $file_path . '">Download this file</a>';
        exit();
    } else {
        echo "File not found.";
    }
}

// Handle file download if the 'download' query parameter is set
if (isset($_GET['download'])) {
    $file_path = $_GET['download'];

    // Validate if the file exists
    if (file_exists($file_path)) {
        // Force the download by setting appropriate headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Pragma: no-cache');
        header('Expires: 0');

        // Read the file and send it to the browser
        readfile($file_path);
        exit();
    } else {
        echo "File not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Child's Documents</title>
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
    <h2>My Child's Documents</h2>

    <!-- Documents Table -->
    <table>
        <thead>
            <tr>
                <th>Document ID</th>
                <th>Student Name</th>
                <th>Batch ID</th>
                <th>Document Name</th>
                <th>Document Type</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if documents are found for the parent’s child
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $file_path = $row['file_path'];
                    // Check if file exists before providing view and download links
                    if (file_exists($file_path)) {
                        echo "<tr>";
                        echo "<td>{$row['document_id']}</td>";
                        echo "<td>{$row['first_name']} {$row['last_name']}</td>";
                        echo "<td>{$row['batch_id']}</td>";
                        echo "<td>{$row['document_name']}</td>";
                        echo "<td>{$row['document_type']}</td>";
                        echo "<td><a href='?view={$file_path}'>View</a></td>"; // Use current page for viewing
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='6'>File not found for {$row['document_name']}.</td></tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='6'>No documents found for your child.</td></tr>";
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
