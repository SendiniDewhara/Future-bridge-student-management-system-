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

// Check if payment_id is passed as a parameter
if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch payment details using the payment_id
    $sql = "SELECT fp.payment_id, fp.student_id, s.first_name, s.last_name, b.batch_name, fp.amount, fp.payment_date
            FROM fee_payments fp
            INNER JOIN students s ON fp.student_id = s.student_id
            INNER JOIN batches b ON s.batch_id = b.batch_id
            WHERE fp.payment_id = '$payment_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $payment_details = $result->fetch_assoc();
    } else {
        die("No payment details found.");
    }
} else {
    die("Invalid request.");
}

$conn->close();

// Generate the invoice HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #2980b9;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th, .invoice-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .invoice-table th {
            background-color: #2980b9;
            color: white;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }
        .back-button {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="invoice">
    <h2>Fee Payment Invoice</h2>
    <p><strong>Student Name:</strong> <?= $payment_details['first_name'] . " " . $payment_details['last_name'] ?></p>
    <p><strong>Batch Name:</strong> <?= $payment_details['batch_name'] ?></p>
    <p><strong>Payment Date:</strong> <?= date('F j, Y', strtotime($payment_details['payment_date'])) ?></p>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Amount</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $payment_details['payment_id'] ?></td>
                <td>LKR <?= number_format($payment_details['amount'], 2) ?></td>
                <td><?= date('F j, Y', strtotime($payment_details['payment_date'])) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total: LKR <?= number_format($payment_details['amount'], 2) ?></p>
    </div>

    <div class="back-button">
        <button onclick="window.print()">Print Invoice</button>
    </div>
</div>

</body>
</html>
