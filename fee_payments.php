<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to get the logged-in user's student_id (assuming session contains this info)
session_start();

// Fetch all students for the filter dropdown
$students_sql = "SELECT student_id, first_name FROM students";
$students_result = $conn->query($students_sql);

// Get filtering criteria from the form
$selected_student = isset($_POST['student_id']) ? $_POST['student_id'] : '';
$selected_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : '';

// Prepare SQL to filter fee payments
$filter_sql = "SELECT fp.payment_id, fp.student_id, s.first_name, fp.amount, fp.payment_date
               FROM fee_payments fp
               INNER JOIN students s ON fp.student_id = s.student_id";

// Apply filtering based on form inputs
$where_clauses = [];
if (!empty($selected_student)) {
    $where_clauses[] = "fp.student_id = '$selected_student'";
}
if (!empty($selected_date)) {
    $where_clauses[] = "fp.payment_date = '$selected_date'";
}
if (!empty($where_clauses)) {
    $filter_sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$result = $conn->query($filter_sql);

// Handle form submission for adding fee payment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    $insert_sql = "INSERT INTO fee_payments (student_id, amount, payment_date) 
                   VALUES ('$student_id', '$amount', '$payment_date')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "<p class='success-message'>New fee payment added successfully!</p>";
    } else {
        echo "<p class='error-message'>Error: " . $insert_sql . "<br>" . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:rgb(84, 136, 192); /* Blue background */
            color: #fff;
        }
        header {
            background-color:rgb(34, 89, 148); /* Darker blue */
            padding: 15px;
            text-align: center;
        }
        h2, h3 {
            color: #fff;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color:rgb(17, 98, 184);
            color: white;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* Transparent background */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 8px 0;
            color: #333;
        }
        select, input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004085;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <header>
        <h1>Fee Payment Management</h1>
    </header>

    <div class="container">

        <!-- Filter Fee Payments Section -->
        <h2>Filter Fee Payments</h2>
        <form method="POST" action="">
            <label for="student_id">Student:</label>
            <select name="student_id" id="student_id">
                <option value="">--Select Student--</option>
                <?php
                if ($students_result->num_rows > 0) {
                    while ($row = $students_result->fetch_assoc()) {
                        $selected = $selected_student == $row['student_id'] ? 'selected' : '';
                        echo "<option value='{$row['student_id']}' $selected>{$row['first_name']}</option>";
                    }
                }
                ?>
            </select>

            <label for="payment_date">Payment Date:</label>
            <input type="date" name="payment_date" id="payment_date" value="<?php echo $selected_date; ?>">

            <button type="submit">Filter</button>
        </form>

        <!-- Display Filtered Fee Payments -->
        <h3>Fee Payments</h3>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Student Name</th>
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
                        echo "<td>{$row['first_name']}</td>";
                        echo "<td>{$row['amount']}</td>";
                        echo "<td>{$row['payment_date']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No fee payments found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Add New Fee Payment Section -->
        <h2>Add New Fee Payment</h2>
        <form method="POST" action="">
            <label for="student_id">Student:</label>
            <select name="student_id" id="student_id" required>
                <option value="">--Select Student--</option>
                <?php
                // Fetch students to show in add payment form
                $students_result = $conn->query("SELECT student_id, first_name FROM students");
                while ($row = $students_result->fetch_assoc()) {
                    echo "<option value='{$row['student_id']}'>{$row['first_name']}</option>";
                }
                ?>
            </select>

            <label for="amount">Amount:</label>
            <input type="number" name="amount" id="amount" step="0.01" required>

            <label for="payment_date">Payment Date:</label>
            <input type="date" name="payment_date" id="payment_date" required>

            <button type="submit" name="add_payment">Add Payment</button>
        </form>

    </div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
