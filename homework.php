<?php
// Include the database connection file
include('db_connection.php');

// Fetch all batches to populate the batch dropdown
try {
    $sql = "SELECT batch_id, batch_name FROM batches";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching batches: " . $e->getMessage();
    exit;
}

$successMessage = ""; // Variable to hold the success message

// Handle form submission for adding homework
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $batch_id = $_POST['batch_id'];
    $student_id = $_POST['student_id'];  // Student ID
    $subject = $_POST['subject'];  // Subject
    $assignment_description = $_POST['description']; // Assignment Description
    $due_date = $_POST['due_date'];  // Due Date

    // File upload handling
    $uploads_dir = 'uploads/'; // Directory where files will be uploaded
    $uploaded_files = [];
    
    if (isset($_FILES['homework_files'])) {
        foreach ($_FILES['homework_files']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['homework_files']['name'][$key]);
            $file_tmp = $_FILES['homework_files']['tmp_name'][$key];
            $file_path = $uploads_dir . $file_name;
            
            if (move_uploaded_file($file_tmp, $file_path)) {
                $uploaded_files[] = $file_path;
            } else {
                echo "Failed to upload file: " . $file_name;
            }
        }
    }

    // Convert the array of uploaded files into a comma-separated string
    $file_paths = implode(',', $uploaded_files);

    // Set default values for the homework submission
    $status = 'pending';  // Default status for newly added homework
    $submission_date = null;  // Submission date is initially null

    try {
        // Insert the homework into the database
        $query = "INSERT INTO homework (student_id, subject, assignment_description, due_date, batch_id, file_paths, status, submission_date) 
                  VALUES (:student_id, :subject, :assignment_description, :due_date, :batch_id, :file_paths, :status, :submission_date)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':assignment_description', $assignment_description);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':batch_id', $batch_id);
        $stmt->bindParam(':file_paths', $file_paths);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':submission_date', $submission_date);

        if ($stmt->execute()) {
            $successMessage = "Homework uploaded successfully!";
        } else {
            echo "<p>Error: Could not add homework.</p>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Homework Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Homework</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #4b79a1; /* Blue background */
            background: linear-gradient(to right, #4b79a1, #283e51); /* Dark blue gradient background */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            width: 50%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* Transparent white box */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px); /* Blur effect for the background */
        }

        h2 {
            text-align: center;
            color: white; /* White text */
        }

        label {
            display: block;
            margin-top: 10px;
            color: #4b79a1; /* Blue text */
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .file-input {
            display: flex;
            flex-direction: column;
        }

        .file-input input {
            padding: 5px;
        }

        small {
            color: #4b79a1;
            font-size: 12px;
        }

        /* Centered success message */
        .success-box {
            display: <?php echo $successMessage ? 'flex' : 'none'; ?>;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent white */
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            color: #0044cc; /* Blue text */
            font-weight: bold;
            width: 40%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <?php if (!empty($successMessage)): ?>
        <div class="success-box">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Add Homework</h2>
        <form method="POST" action="homework.php" enctype="multipart/form-data">
            <label for="batch_id">Select Batch:</label>
            <select name="batch_id" required>
                <option value="">Select Batch</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?= $batch['batch_id'] ?>"><?= $batch['batch_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" required>

            <label for="subject">Subject:</label>
            <input type="text" name="subject" required>

            <label for="description">Assignment Description:</label>
            <textarea name="description" required></textarea>

            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" required>

            <label for="homework_files">Upload Files (Images, Documents, etc.):</label>
            <div class="file-input">
                <input type="file" name="homework_files[]" multiple>
                <small>You can select multiple files (images, PDFs, etc.).</small>
            </div>

            <button type="submit">Add Homework</button>
        </form>
    </div>

</body>
</html>
