<?php
require 'vendor/autoload.php';  // Include Composer's autoload file
use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection (replace with your own connection settings)
$pdo = new PDO('mysql:host=localhost;dbname=student_management_system', 'username', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle file upload
if (isset($_POST['submit'])) {
    // Check if a file is uploaded
    if ($_FILES['file']['error'] == 0) {
        // Get the file information
        $file = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Check if the file is an Excel file or Word file
        if ($fileExtension == 'xlsx' || $fileExtension == 'xls') {
            // Handle Excel file
            try {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = [];

                foreach ($sheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    $cells = [];
                    foreach ($cellIterator as $cell) {
                        $cells[] = $cell->getFormattedValue();
                    }
                    $rows[] = $cells;
                }

                // Store timetable data in the database
                foreach ($rows as $row) {
                    // Assuming the columns in Excel are student_id, day_of_week, start_time, end_time, subject
                    $stmt = $pdo->prepare("INSERT INTO timetable (student_id, day_of_week, start_time, end_time, subject) 
                                          VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$row[0], $row[1], $row[2], $row[3], $row[4]]);
                }

                echo "Timetable uploaded and data saved successfully!";
            } catch (Exception $e) {
                echo "Error reading Excel file: " . $e->getMessage();
            }
        } elseif ($fileExtension == 'docx' || $fileExtension == 'doc') {
            // Handle Word document
            $content = file_get_contents($file);
            $uploadDir = 'uploads/';
            $filePath = $uploadDir . basename($fileName);
            move_uploaded_file($file, $filePath);
            echo "Word document uploaded successfully.";
        } else {
            echo "Invalid file type. Only Excel or Word files are allowed.";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Timetable</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Upload Timetable (Excel or Word)</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Upload Timetable File (Excel or Word):</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
</body>
</html>
