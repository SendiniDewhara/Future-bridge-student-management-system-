<?php
include('db_connection.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=term_marks_list.csv");
header("Pragma: no-cache");
header("Expires: 0");

echo "Mark ID,Student ID,Term,Subject,Marks\n";

try {
    $query = "SELECT mark_id, student_id, term, subject, marks FROM term_marks ORDER BY term ASC, student_id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['mark_id']},{$row['student_id']},Term {$row['term']},{$row['subject']},{$row['marks']}\n";
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
