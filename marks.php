<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel - Coding-Zon Demo</title>
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #4b79a1, #283e51); /* Background gradient */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.85); /* Light transparent background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #283e51;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 1em;
            color: #333;
            margin-bottom: 10px;
        }

        .form-group input[type="file"] {
            background-color: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .btn-success {
            background-color: #00B8D4;
            border: none;
            padding: 12px 20px;
            font-size: 1.2em;
            width: 100%;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-success:hover {
            background-color: #007b8f;
        }

        .btn {
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Upload Marks Excel Sheet</h1>

    <form method="POST" action="excelUpload.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Choose Excel File</label>
            <input type="file" name="file" class="form-control" id="file" required>
        </div>
        <div class="form-group">
            <button type="submit" name="Submit" class="btn btn-success">Upload</button>
        </div>
    </form>
</div>

<!-- Bootstrap 4 JS and jQuery (for potential form handling or UI enhancements) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
