<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Delete exam results for this user
$stmt = $conn->prepare("DELETE FROM exam_results WHERE user_id = ?");
$stmt->execute([$user_id]);

// Redirect back to dashboard with a success message
header("Location: dashboard.php?success=Recommendations reset successfully. You can now re-enter your results.");
exit;
?>
