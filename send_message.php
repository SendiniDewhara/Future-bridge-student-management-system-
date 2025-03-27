<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in and is a parent
if (!isset($_SESSION['user_id']) || $_SESSION['user_type_id'] != 3) {
    header("Location: login.php");
    exit();
}

include('db_config.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    $user_id = $_SESSION['user_id'];

    // Validate message
    if (empty($message)) {
        header("Location: messages.php?error=Message cannot be empty");
        exit();
    }

    // Insert the message into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, message, sent_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $message]);
        header("Location: messages.php?success=Message sent successfully");
        exit();
    } catch (PDOException $e) {
        // Handle database error
        header("Location: messages.php?error=An error occurred while sending the message. Please try again.");
        exit();
    }
} else {
    header("Location: messages.php");
    exit();
}
?>
