<?php
session_start();
require 'vendor/autoload.php'; // Composer's autoload file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'student_management_system';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Ensure session user_id exists
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$parent_id = $_SESSION['user_id'];

// Fetch unread notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
$stmt->execute([$parent_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debugging: Check if notifications exist
if (!$notifications) {
    echo "<p>No new notifications.</p>";
}

// Mark notifications as read only if they exist
if ($notifications) {
    $updateStmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
    $updateStmt->execute([$parent_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Notifications</title>
    <style>
        .popup {
            position: fixed;
            top: 20%;
            right: 10%;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            display: none;
            z-index: 9999;
        }
        .popup h3 {
            margin-top: 0;
            color: #333;
        }
        .popup p {
            color: #555;
        }
        .close-popup {
            cursor: pointer;
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<?php if ($notifications): ?>
    <div class="popup" id="notificationPopup">
        <h3>You have new notifications!</h3>
        <?php foreach ($notifications as $notification): ?>
            <p><?php echo htmlspecialchars($notification['message']); ?></p>
        <?php endforeach; ?>
        <button class="close-popup" onclick="closePopup()">Close</button>
    </div>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let popup = document.getElementById('notificationPopup');

        // Show the popup only if it exists
        if (popup) {
            popup.style.display = 'block';
        }

        console.log("Notification popup script is running.");
    });

    function closePopup() {
        document.getElementById('notificationPopup').style.display = 'none';
    }
</script>

</body>
</html>
