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
<html>
<head>
    <title>Upload Excel File - View Data</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Upload Marks Excel Sheet</h1>

    <!-- Upload Form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Upload Excel File</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <div class="form-group">
            <button type="submit" name="Submit" class="btn btn-success">Upload</button>
        </div>
    </form>

    <!-- Display Data -->
    <?php if (isset($rows) && !empty($rows)): ?>
        <h2>Uploaded Excel Data</h2>
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

</body>
</html>
