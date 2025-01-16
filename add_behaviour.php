<?php
// Include the database connection file
include 'db_connection.php';

// Initialize variables for error/success messages
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $studentId = $_POST['student_id'];
    $behaviorNotes = $_POST['behavior_notes'];
    $mentalHealthStatus = $_POST['mental_health_status'];

    try {
        // Insert data into the `student_behavior` table
        $sql = "INSERT INTO student_behavior (student_id, behavior_notes, mental_health_status) 
                VALUES (:student_id, :behavior_notes, :mental_health_status)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':behavior_notes', $behaviorNotes, PDO::PARAM_STR);
        $stmt->bindParam(':mental_health_status', $mentalHealthStatus, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        $successMessage = "Behavior record added successfully!";
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
    <title>Add Behavior</title>
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
    <h1>Add Behavior Record</h1>

    <!-- Display messages -->
    <?php if ($successMessage): ?>
        <div class="message success"><?php echo $successMessage; ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <!-- Behavior Form -->
    <form method="POST">
        <label for="student_id">Student ID</label>
        <input type="number" name="student_id" id="student_id" required>

        <label for="behavior_notes">Behavior Notes</label>
        <textarea name="behavior_notes" id="behavior_notes" required></textarea>

        <label for="mental_health_status">Mental Health Status</label>
        <textarea name="mental_health_status" id="mental_health_status" required></textarea>

        <button type="submit">Add Behavior</button>
    </form>
</body>
</html>
