<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Special Needs Record
if (isset($_POST['add'])) {
    $student_id = $_POST['student_id'];
    $mental_status = $_POST['mental_status'];
    $physical_status = $_POST['physical_status'];
    $parent_request = $_POST['parent_request'];

    $stmt = $conn->prepare("INSERT INTO special_needs (student_id, mental_status, physical_status, parent_request) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $student_id, $mental_status, $physical_status, $parent_request);

    if ($stmt->execute()) {
        echo "Special needs record added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch record for editing
$edit_record = null;
if (isset($_GET['edit'])) {
    $need_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM special_needs WHERE need_id = ?");
    $stmt->bind_param("i", $need_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_record = $result->fetch_assoc();
    $stmt->close();
}

// Update Special Needs Record
if (isset($_POST['update'])) {
    $need_id = $_POST['need_id'];
    $mental_status = $_POST['mental_status'];
    $physical_status = $_POST['physical_status'];
    $parent_request = $_POST['parent_request'];

    $stmt = $conn->prepare("UPDATE special_needs SET mental_status = ?, physical_status = ?, parent_request = ? WHERE need_id = ?");
    $stmt->bind_param("sssi", $mental_status, $physical_status, $parent_request, $need_id);

    if ($stmt->execute()) {
        echo "Special needs record updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Delete Special Needs Record
if (isset($_GET['delete'])) {
    $need_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM special_needs WHERE need_id = ?");
    $stmt->bind_param("i", $need_id);

    if ($stmt->execute()) {
        echo "Special needs record deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all special needs records
function fetchSpecialNeeds($conn)
{
    $sql = "SELECT special_needs.*, CONCAT(students.first_name, ' ', students.last_name) AS student_name 
            FROM special_needs 
            JOIN students ON special_needs.student_id = students.student_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['need_id']}</td>
                    <td>{$row['student_name']}</td>
                    <td>{$row['mental_status']}</td>
                    <td>{$row['physical_status']}</td>
                    <td>{$row['parent_request']}</td>
                    <td>
                        <a href='?edit={$row['need_id']}'>Edit</a>
                        <a href='?delete={$row['need_id']}' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No records found.</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Needs Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #4b79a1, #283e51); /* Dark blue gradient background */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Adjust alignment */
            flex-direction: column;
            height: 100vh;
            padding-top:130px; /* Add padding at the top */
        }

        .container {
            width: 95%;
            max-width: 1600px; /* Adjust the max-width for larger screens */
            background: rgba(255, 255, 255, 0.1); /* Transparent background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        h1, h2 {
            text-align: center;
            color: #00B8D4; /* Teal for heading */
            margin-bottom: 20px;
        }

        form {
            background-color: rgba(255, 255, 255, 0.2); /* Transparent form background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        label {
            color: #fff;
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2); /* Light transparent background for input fields */
            color: #fff;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background: #00B8D4; /* Teal button */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            font-weight: bold;
            width: 100%;
        }

        button:hover {
            background: #007b8f; /* Darker teal on hover */
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #283e51;
            color: #fff;
        }

        a {
            text-decoration: none;
            color: #00B8D4;
            margin: 5px;
            font-weight: bold;
        }

        a:hover {
            color: #007bff;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Manage Special Needs</h1>

    <!-- Add/Update Form -->
    <h2><?php echo $edit_record ? "Edit Special Needs" : "Add Special Needs"; ?></h2>
    <form method="POST">
        <?php if ($edit_record): ?>
            <input type="hidden" name="need_id" value="<?php echo $edit_record['need_id']; ?>">
        <?php endif; ?>
        <label for="student_id">Student ID:</label>
        <input type="number" name="student_id" value="<?php echo $edit_record['student_id'] ?? ''; ?>" required <?php echo $edit_record ? "readonly" : ""; ?>><br>
        <label for="mental_status">Mental Status:</label>
        <textarea name="mental_status" required><?php echo $edit_record['mental_status'] ?? ''; ?></textarea><br>
        <label for="physical_status">Physical Status:</label>
        <textarea name="physical_status" required><?php echo $edit_record['physical_status'] ?? ''; ?></textarea><br>
        <label for="parent_request">Parent Request:</label>
        <textarea name="parent_request"><?php echo $edit_record['parent_request'] ?? ''; ?></textarea><br>
        <button type="submit" name="<?php echo $edit_record ? "update" : "add"; ?>">
            <?php echo $edit_record ? "Update" : "Add"; ?>
        </button>
    </form>

    <!-- View Records -->
    <h2>Special Needs Records</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>Need ID</th>
                <th>Student Name</th>
                <th>Mental Status</th>
                <th>Physical Status</th>
                <th>Parent Request</th>
                <th>Actions</th>
            </tr>
            <?php fetchSpecialNeeds($conn); ?>
        </table>
    </div>

    <?php $conn->close(); ?>
</div>

</body>
</html>
