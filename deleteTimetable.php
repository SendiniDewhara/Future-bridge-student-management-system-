<?php
require 'db_connection.php'; // Include database connection

// Check if timetable ID is provided
if (isset($_GET['timetable_id'])) {
    $timetable_id = $_GET['timetable_id'];

    try {
        // Delete the timetable entry
        $stmt = $pdo->prepare("DELETE FROM timetable WHERE timetable_id = ?");
        $stmt->execute([$timetable_id]);

        echo "Timetable entry deleted successfully!";
        header("Location: timetable.php"); // Redirect back to the timetable page
        exit;
    } catch (PDOException $e) {
        echo "Error deleting timetable: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
    exit;
}
?>
