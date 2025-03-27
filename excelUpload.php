<?php
require 'vendor/autoload.php';  // Include Composer's autoload file

use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle the form submission
if (isset($_POST['Submit'])) {
    // Check if the file was uploaded without errors
    if ($_FILES['file']['error'] == 0) {
        // Get the file's temporary path
        $filePath = $_FILES['file']['tmp_name'];

        // Load the Excel file
        $spreadsheet = IOFactory::load($filePath);

        // Get the active sheet (the first sheet)
        $sheet = $spreadsheet->getActiveSheet();

        // Read the data from the Excel sheet (for example, all rows and columns)
        $rows = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);  // Iterate over all cells
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getFormattedValue();  // Get cell value
            }
            $rows[] = $cells;  // Add the row's data to the array
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Marks Excel Sheet</title>
    <!-- Bootstrap 4 CSS for responsive layout -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #003366; /* Dark Blue Background */
            color: white; /* White font color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-top: 90px; /* Padding added to the top of the page */
        }

        .container {
            background: rgba(255, 255, 255, 0.15); /* Slightly transparent white */
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 900px;
        }

        h1 {
            font-size: 2.5em;
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            padding: 15px;
            font-size: 1.1em;
            background-color: rgba(255, 255, 255, 0.9); /* Light background for inputs */
            border: 1px solid #ddd;
            color: #003366; /* Dark blue text color */
        }

        .btn-success {
            background-color: transparent;
            border: 2px solid #00B8D4;
            color: #00B8D4;
            padding: 15px 30px;
            font-size: 1.2em;
            width: 100%;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #00B8D4;
            color: white;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 1.1em;
        }

        th {
            background-color: #003366;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Upload Marks Excel Sheet</h1>

    <!-- Upload Form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Upload Excel File</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <div class="form-group">
            <button type="submit" name="Submit" class="btn btn-success">Upload</button>
        </div>
    </form>

    <!-- Display Data -->
    <?php if (isset($rows) && !empty($rows)): ?>
        <h2 class="mt-5 text-center">Uploaded Excel Data</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <?php
                    // Display header (first row)
                    foreach ($rows[0] as $header) {
                        echo "<th>" . htmlspecialchars($header) . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display the rest of the rows
                foreach (array_slice($rows, 1) as $row) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and jQuery (for potential form handling or UI enhancements) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
