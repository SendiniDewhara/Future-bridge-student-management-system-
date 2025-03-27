<?php
session_start();
if ($_SESSION['user_type_id'] != 2) { 
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_management_system';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $student_id = $conn->real_escape_string($_GET['id']);

    // DELETE related records first
    $conn->query("DELETE FROM attendance WHERE student_id = '$student_id'");
    $conn->query("DELETE FROM government_exam_results WHERE student_id = '$student_id'");

    // DELETE student after removing related records
    $delete_student = "DELETE FROM students WHERE student_id = '$student_id'";
    if ($conn->query($delete_student) === TRUE) {
        echo "<script>alert('Student deleted successfully!'); window.location='manage_students.php';</script>";
    } else {
        echo "<script>alert('Error deleting student! Check dependencies.'); window.location='manage_students.php';</script>";
    }
} else {
    header("Location: manage_students.php");
}

$conn->close();
?>
