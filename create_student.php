<?php
// Include the database connection file
include 'db_connection.php';

// Initialize variables for error/success messages
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize inputs
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $guardianName = trim($_POST['guardian_name']);
    $guardianContact = trim($_POST['guardian_contact']);
    $admissionDate = $_POST['admission_date'];
    $leavingDate = $_POST['leaving_date'] ?? null;
    $mentalStatus = trim($_POST['mental_status']);
    $physicalStatus = trim($_POST['physical_status']);
    $nurseryProgress = trim($_POST['nursery_progress']);
    $schoolProgress = trim($_POST['school_progress']);
    $parentRequest = trim($_POST['parent_request']);
    $admittedYear = $_POST['admitted_year'];  // New field for admitted year (batch)

    try {
        // Insert data into the `students` table
        $sql = "INSERT INTO students (
                    first_name, last_name, dob, gender, guardian_name, guardian_contact,
                    admission_date, leaving_date, mental_status, physical_status,
                    nursery_progress, school_progress, parent_request, admitted_year
                ) VALUES (
                    :first_name, :last_name, :dob, :gender, :guardian_name, :guardian_contact,
                    :admission_date, :leaving_date, :mental_status, :physical_status,
                    :nursery_progress, :school_progress, :parent_request, :admitted_year
                )";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':guardian_name', $guardianName);
        $stmt->bindParam(':guardian_contact', $guardianContact);
        $stmt->bindParam(':admission_date', $admissionDate);
        $stmt->bindParam(':leaving_date', $leavingDate);
        $stmt->bindParam(':mental_status', $mentalStatus);
        $stmt->bindParam(':physical_status', $physicalStatus);
        $stmt->bindParam(':nursery_progress', $nurseryProgress);
        $stmt->bindParam(':school_progress', $schoolProgress);
        $stmt->bindParam(':parent_request', $parentRequest);
        $stmt->bindParam(':admitted_year', $admittedYear);  // Binding the admitted year

        // Execute the statement
        $stmt->execute();

        $successMessage = "Student record added successfully!";
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
    <title>Create Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            margin: 0;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Create Student</h1>

    <!-- Display messages -->
    <?php if ($successMessage): ?>
        <div class="message success"><?php echo $successMessage; ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <!-- Student Form -->
    <form method="POST">
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

        <label for="admission_date">Admission Date</label>
        <input type="date" name="admission_date" id="admission_date" required>

        <label for="leaving_date">Leaving Date</label>
        <input type="date" name="leaving_date" id="leaving_date">

        <label for="mental_status">Mental Status</label>
        <textarea name="mental_status" id="mental_status"></textarea>

        <label for="physical_status">Physical Status</label>
        <textarea name="physical_status" id="physical_status"></textarea>

        <label for="nursery_progress">Nursery Progress</label>
        <textarea name="nursery_progress" id="nursery_progress"></textarea>

        <label for="school_progress">School Progress</label>
        <textarea name="school_progress" id="school_progress"></textarea>

        <label for="parent_request">Parent Requests</label>
        <textarea name="parent_request" id="parent_request"></textarea>

        <!-- Admitted Year (Batch) Field -->
        <label for="admitted_year">Admitted Year (Batch)</label>
        <input type="number" name="admitted_year" id="admitted_year" value="<?php echo date('Y'); ?>" required>

        <button type="submit">Add Student</button>
    </form>
</body>
</html>
