<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default filter values
$student_id_filter = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$batch_filter = isset($_GET['batch_id']) ? $_GET['batch_id'] : '';

// SQL query to fetch fee payments based on filter, including student details
$sql = "SELECT fp.payment_id, fp.student_id, s.first_name, b.batch_name, fp.amount, fp.payment_date
        FROM fee_payments fp
        INNER JOIN students s ON fp.student_id = s.student_id
        INNER JOIN batches b ON s.batch_id = b.batch_id
        WHERE 1=1";

// Apply filters
if ($student_id_filter != '') {
    $sql .= " AND fp.student_id = '$student_id_filter'";
}

if ($batch_filter != '') {
    $sql .= " AND s.batch_id = '$batch_filter'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent View - Fee Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: #fff;
            margin: 0;
            padding: 0;
        }

        h2, h3 {
            text-align: center;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2980b9;
        }

        td {
            background-color: rgba(255, 255, 255, 0.2);
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.15);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        form {
            background: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            width: 60%;
            margin: 20px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        input[type="text"] {
            padding: 8px;
            margin: 10px 0;
            width: 45%;
            border: 2px solid #2980b9;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.6);
            color: #333;
        }

        button {
            padding: 10px 20px;
            background-color: #2980b9;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            align
        }

        button:hover {
            background-color: #1abc9c;
        }

        a {
            color: #2980b9;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Fee Payments (Parent View)</h2>

    <h3>Filter Fee Payments by Student ID and Batch</h3>
    <form method="GET" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" value="<?= $student_id_filter ?>"> <br>

        <label for="batch">Batch:</label>
        <input type="text" name="batch" id="batch" value="<?= $batch_filter ?>"><br>

        <button type="submit">Filter</button>
    </form>

    <h3>Existing Fee Payments</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Batch Name</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Invoice</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['payment_id']}</td>";
                    echo "<td>{$row['student_id']}</td>";
                    echo "<td>{$row['first_name']}</td>";
                    echo "<td>{$row['batch_name']}</td>";
                    echo "<td>{$row['amount']}</td>";
                    echo "<td>{$row['payment_date']}</td>";
                    echo "<td><a href='generate_invoice.php?payment_id={$row['payment_id']}' target='_blank'>Download Invoice</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No fee payments found</td></tr>";
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
