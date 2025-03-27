<?php
// Start the session at the beginning of the script
session_start();

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
        echo "No student linked to this parent.";
        exit();
    }

    // Fetch textbooks for each student linked to the parent
    $student_ids_placeholder = implode(',', $student_ids); // Prepare the list of student IDs for SQL query

    $sql = "SELECT t.textbook_id, t.student_id, s.first_name, s.last_name, t.textbook_name, t.received, t.distribution_date
            FROM textbooks t
            INNER JOIN students s ON t.student_id = s.student_id
            WHERE t.student_id IN ($student_ids_placeholder)"; // Fetch textbooks for the parent's children

    $result = $conn->query($sql);
} else {
    echo "You must be logged in to view this page.";
    exit();
}
?>

<!-- Parent Dashboard HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Textbook Distribution</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right,rgb(61, 113, 182),rgb(51, 107, 172)); /* Blue to white gradient */
            color: #333; /* Dark text for readability */
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        header {
            background-color: rgba(92, 126, 189, 0.85); /* Semi-transparent blue */
            color: white;
            text-align: center;
            padding: 20px;
        }

        .container {
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
            background-color: rgba(255, 255, 255, 0.8); /* White with slight transparency */
            padding: 30px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color:rgb(57, 105, 153);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #cccccc;
        }

        th, td {
            padding: 12px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.6); /* Slight transparency for table rows */
        }

        th {
            background-color:rgb(76, 133, 173); /* Blue header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: rgba(230, 230, 230, 0.5); /* Light gray for alternate rows */
        }

        tr:hover {
            background-color: rgba(0, 170, 255, 0.3); /* Hover effect */
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            table, th, td {
                font-size: 14px; /* Smaller font size for smaller screens */
            }

            .container {
                width: 100%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Parent Dashboard - Textbook Distribution</h1>
    </header>

    <div class="container">
        <h2>Your Children's Textbook Distribution Status</h2>

        <table>
            <thead>
                <tr>
                    <th>Textbook ID</th>
                    <th>Student Name</th>
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
                        echo "<td>{$row['first_name']} {$row['last_name']}</td>";
                        echo "<td>{$row['textbook_name']}</td>";
                        echo "<td>" . ($row['received'] ? 'Yes' : 'No') . "</td>";
                        echo "<td>{$row['distribution_date']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No textbook distribution records found for your children</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
