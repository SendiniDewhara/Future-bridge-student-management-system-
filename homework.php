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

// Handle form submission for adding homework
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $batch_id = $_POST['batch_id'];
    $homework_title = $_POST['homework_title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

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

    try {
        // Insert the homework into the database
        $query = "INSERT INTO homework (homework_title, description, due_date, batch_id, file_paths) 
                  VALUES (:homework_title, :description, :due_date, :batch_id, :file_paths)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':homework_title', $homework_title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':batch_id', $batch_id);
        $stmt->bindParam(':file_paths', $file_paths);

        if ($stmt->execute()) {
            echo "<p>Homework added successfully!</p>";
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
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 10px;
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
    </style>
</head>
<body>
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

            <label for="homework_title">Homework Title:</label>
            <input type="text" name="homework_title" required>

            <label for="description">Description:</label>
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
