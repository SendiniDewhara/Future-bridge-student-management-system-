<?php
// Database connection
$host = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "student_management_system";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false; // Initialize success flag

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $behavior_notes = $_POST['behavior_notes'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO student_behavior (student_id, behavior_notes) VALUES (?, ?)");
    $stmt->bind_param("is", $student_id, $behavior_notes);

    if ($stmt->execute()) {
        $success = true; // Set success flag
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Behavior</title>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4b79a1, #283e51);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 90%;
            max-width: 600px;
            text-align: center;
        }
        .form-container h1 {
            font-size: 28px;
            color: #fff;
            margin-bottom: 20px;
        }
        .form-grid {
            display: flex;
            gap: 20px;
        }
        .form-column {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.7);
            color: #333;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
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
        .top-right {
            position: fixed; 
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        .top-right a {
            background-color: #004080;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .top-right a:hover {
            background-color: #003366;
        }
        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.6);
            padding: 20px 30px;
            border-radius: 10px;
            color: #004080;
            text-align: center;
            font-size: 18px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="top-right">
        <a href="view_behavior.php">View Behavior Records</a>
    </div>

    <div class="form-container">
        <h1>Add Behavior Record</h1>

        <form method="POST">
            <div class="form-grid">
                <div class="form-column">
                    <label for="student_id">Student ID</label>
                    <input type="number" name="student_id" id="student_id" required>

                    <label for="behavior_notes">Behavior Notes</label>
                    <textarea name="behavior_notes" id="behavior_notes" required></textarea>
                </div>
            </div>
            <button type="submit">Add Behavior</button>
        </form>
    </div>

    <?php if ($success): ?>
    <script>
        var successMessage = document.createElement('div');
        successMessage.classList.add('success-message');
        successMessage.innerHTML = "Behavior Record Added Successfully!";
        document.body.appendChild(successMessage);

        successMessage.style.display = "block";
        setTimeout(function() {
            successMessage.style.display = "none";
        }, 3000);

        confetti({
            particleCount: 100,
            spread: 70,
            origin: { x: 0.5, y: 0.5 }
        });
    </script>
    <?php endif; ?>
</body>
</html>
