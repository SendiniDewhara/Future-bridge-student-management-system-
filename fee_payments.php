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

// SQL query to fetch fee payments based on filter, including first_name from students table
$sql = "SELECT fp.payment_id, fp.student_id, s.first_name, s.batch, fp.amount, fp.payment_date
        FROM fee_payments fp
        INNER JOIN students s ON fp.student_id = s.student_id
        WHERE 1=1";

// Apply filters
if ($student_id_filter != '') {
    $sql .= " AND fp.student_id = '$student_id_filter'";
}

if ($batch_filter != '') {
    $sql .= " AND s.batch = '$batch_filter'";
}

$result = $conn->query($sql);

// Handle form submission for adding fee payment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    $insert_sql = "INSERT INTO fee_payments (student_id, amount, payment_date) 
                   VALUES ('$student_id', '$amount', '$payment_date')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "New fee payment added successfully!";
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }
}

?>

<!-- Front-end HTML and Form for Filtering and Adding Fee Payments -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Fee Payments</title>
</head>
<body>

    <h2>Fee Payments</h2>

    <h3>Filter Fee Payments by Student ID and Batch</h3>
    <form method="GET" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" value="<?= $student_id_filter ?>">

        <label for="batch">Batch:</label>
        <input type="text" name="batch" id="batch" value="<?= $batch_filter ?>">

        <button type="submit">Filter</button>
    </form>

    <h3>Existing Fee Payments</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Student ID</th>
                <th>Student Name</th>  <!-- Displaying First Name -->
                <th>Batch</th> <!-- Displaying Batch -->
                <th>Amount</th>
                <th>Payment Date</th>
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
                    echo "<td>{$row['batch']}</td>";  
                    echo "<td>{$row['amount']}</td>";
                    echo "<td>{$row['payment_date']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No fee payments found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>

    <h3>Add New Fee Payment</h3>
    <form method="POST" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" required>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" step="0.01" required>

        <label for="payment_date">Payment Date:</label>
        <input type="date" name="payment_date" id="payment_date" required>

        <button type="submit">Add Payment</button>
    </form>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
