<?php
// security_check.php

function checkParentAccess($pdo, $parent_user_id) {
    $stmt = $pdo->prepare("
        SELECT student_id 
        FROM users 
        WHERE user_id = ? 
        AND user_type_id = 3
    ");
    $stmt->execute([$parent_user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add this at the top of each child-related page (child_performance.php, attendance.php, etc.):
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db_config.php');

if (!isset($_SESSION['user_id']) || 
    $_SESSION['user_type_id'] != 3 || 
    !checkParentAccess($pdo, $_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>