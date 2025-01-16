<?php
// Include the database connection file
include 'db_connection.php';

// Initialize variables for error/success messages
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $studentId = $_POST['student_id'];
    $mentalHealthStatus = $_POST['mental_health_status'];
    $observationDate = $_POST['observation_date'];

    try {
        // Insert data into the `mental_health` table
        $sql = "INSERT INTO mental_health (student_id, mental_health_status, observation_date) 
                VALUES (:student_id, :mental_health_status, :observation_date)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':mental_health_status', $mentalHealthStatus, PDO::PARAM_STR);
        $stmt->bindParam(':observation_date', $observationDate, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        $successMessage = "Mental health record added successfully!";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Tracking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Add Mental Health Record</h1>

    <!-- Display messages -->
    <?php if ($successMessage): ?>
        <div class="message success"><?php echo $successMessage; ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <!-- Mental Health Form -->
    <form method="POST">
        <label for="student_id">Student ID</label>
        <input type="number" name="student_id" id="student_id" required>

        <label for="mental_health_status">Mental Health Status</label>
        <textarea name="mental_health_status" id="mental_health_status" required></textarea>

        <label for="observation_date">Observation Date</label>
        <input type="date" name="observation_date" id="observation_date" required>

        <button type="submit">Add Mental Health Record</button>
    </form>
</body>
</html>
