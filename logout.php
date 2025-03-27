<?php
session_start();

// Check user type and redirect accordingly
if (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];

    // Destroy session
    session_unset();
    session_destroy();

    // Redirect based on user type
    if ($user_type === 'nursery_admin') {
        header("Location: nursery_admin_login.php");
    } elseif ($user_type === 'school_admin') {
        header("Location: school_admin_login.php");
    } elseif ($user_type === 'parent') {
        header("Location: parent_login.php");
    } else {
        header("Location: index.php"); // Default redirect
    }
} else {
    header("Location: index.php"); // If session is not set
}
exit();
?>
