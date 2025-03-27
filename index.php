<?php
// Database connection (update credentials accordingly)
$host = "localhost";
$username = "root";
$password = "";
$database = "student_management_system";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define user types and their login pages
$userTypes = [
    "Nursery Admin" => "nursery_admin_login.php",
    "Parent" => "parent_login.php",
    "School Admin" => "school_admin_login.php"
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User Type</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #4b79a1, #283e51);
            margin: 0;
            color: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 50px;
            font-weight: 700;
            color: #00B8D4;
            letter-spacing: 2px;
        }

        .header p {
            font-size: 18px;
            color: #A0AEC0;
            margin-top: 10px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .tile {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 220px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .tile:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .tile a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            font-size: 18px;
            display: block;
        }

        .tile a:hover {
            color: #007bff;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #A0AEC0;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Future Bridge</h1>
    <p>Connecting the Future, One Step at a Time</p>
</div>

<div class="container">
    <?php
    foreach ($userTypes as $userType => $loginPage) {
        echo "<div class='tile'><a href='$loginPage'>$userType</a></div>";
    }
    ?>
</div>

<div class="footer">
    <p>&copy; 2025 Future Bridge. All Rights Reserved.</p>
</div>

</body>
</html>
