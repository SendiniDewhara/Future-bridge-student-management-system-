<?php
// Database connection
$host = "localhost"; // Update if needed
$user = "root"; // Update with your database username
$password = ""; // Update with your database password
$database = "student_management_system"; // Update with your actual database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch batches
$sql = "SELECT batch_id, batch_name FROM batches";
$result = $conn->query($sql);
$batches = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $batches[] = $row;
    }
}

// Insert student data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $guardian_name = $_POST['guardian_name'];
    $guardian_contact = $_POST['guardian_contact'];
    $admission_date = $_POST['admission_date'];
    $leaving_date = $_POST['leaving_date'] ?: NULL;
    $mental_status = $_POST['mental_status'];
    $physical_status = $_POST['physical_status'];
    $batch_id = $_POST['batch_id'];
    
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, dob, gender, guardian_name, guardian_contact, admission_date, leaving_date, mental_status, physical_status, batch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssi", $first_name, $last_name, $dob, $gender, $guardian_name, $guardian_contact, $admission_date, $leaving_date, $mental_status, $physical_status, $batch_id);
    
    if ($stmt->execute()) {
        $success = true;
    } else {
        $success = false;
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            max-width: 800px;
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
        input, textarea, select {
            width: 100%;
            padding: 10px;
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
        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.5); /* Transparent box */
            padding: 20px 30px;
            border-radius: 10px;
            color: #003366; /* Dark blue text */
            text-align: center;
            font-size: 18px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Create Student</h1>
        <form method="POST">
            <div class="form-grid">
                <div class="form-column">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required>

                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required>

                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob" required>

                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>

                    <label for="guardian_name">Guardian Name</label>
                    <input type="text" name="guardian_name" id="guardian_name">

                    <label for="guardian_contact">Guardian Contact</label>
                    <input type="text" name="guardian_contact" id="guardian_contact">
                </div>
                <div class="form-column">
                    <label for="admission_date">Admission Date</label>
                    <input type="date" name="admission_date" id="admission_date" required>

                    <label for="leaving_date">Leaving Date</label>
                    <input type="date" name="leaving_date" id="leaving_date">

                    <label for="mental_status">Mental Status</label>
                    <textarea name="mental_status" id="mental_status"></textarea>

                    <label for="physical_status">Physical Status</label>
                    <textarea name="physical_status" id="physical_status"></textarea>

                    <label for="batch_id">Select Batch</label>
                    <select name="batch_id" id="batch_id" required>
                        <option value="">-- Select Batch --</option>
                        <?php foreach ($batches as $batch) { ?>
                            <option value="<?php echo $batch['batch_id']; ?>"><?php echo $batch['batch_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <button type="submit">Add Student</button>
        </form>
    </div>

    <?php if (isset($success) && $success): ?>
    <script>
        // Show success message
        var successMessage = document.createElement('div');
        successMessage.classList.add('success-message');
        successMessage.innerHTML = "Student account created successfully!";
        document.body.appendChild(successMessage);

        // Show success message with animation
        successMessage.style.display = "block";
        setTimeout(function() {
            successMessage.style.display = "none";
        }, 3000);

        // Flying celebration effect
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { x: 0.5, y: 0.5 }
        });
    </script>
    <?php endif; ?>
</body>
</html>
