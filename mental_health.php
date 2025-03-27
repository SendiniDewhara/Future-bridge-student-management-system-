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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4b79a1, #283e51);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.7);
            color: #333;
            font-size: 16px;
        }

        input:focus, textarea:focus {
            outline: none;
            border: 2px solid #004080;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #004080;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #003366;
        }

        .message-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 50, 0.8);
            padding: 20px;
            border-radius: 10px;
            color: #ffffff;
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
            display: none;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.5);
        }

        @keyframes popUp {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        .show-message {
            display: block;
            animation: popUp 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add Mental Health Record</h1>
        
        <!-- Display messages -->
        <?php if ($successMessage): ?>
            <div id="successMessage" class="message-container show-message">🎉 <?php echo $successMessage; ?> 🎉</div>
            <script>
                setTimeout(() => {
                    document.getElementById("successMessage").style.display = "none";
                }, 3000);
            </script>
        <?php elseif ($errorMessage): ?>
            <div class="message-container show-message" style="background: rgba(50, 0, 0, 0.8);">❌ <?php echo $errorMessage; ?> ❌</div>
        <?php endif; ?>

        <!-- Mental Health Form -->
        <form method="POST">
            <input type="number" name="student_id" placeholder="Enter Student ID" required>
            <textarea name="mental_health_status" placeholder="Describe the mental health status" required></textarea>
            <input type="date" name="observation_date" required>
            <button type="submit">Add Record</button>
        </form>
    </div>
</body>
</html>
