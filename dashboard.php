<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's name
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$username = $user ? htmlspecialchars($user['full_name']) : 'User';

// Check if the user has submitted exam results
$stmt = $conn->prepare("SELECT COUNT(*) FROM exam_results WHERE user_id = ?");
$stmt->execute([$user_id]);
$hasResults = $stmt->fetchColumn() > 0;

$sql = "SELECT is_active FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['is_active'] == 0) {
    session_destroy();
    header("Location: login.php?error=deactivated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function checkResults(hasResults) {
            if (!hasResults) {
                alert("⚠️ Please enter your exam results first to view course recommendations.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="profile.php">My Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Welcome back, <?= $username ?>! 👋</h2>
    <p>This is your dashboard. Choose an option below:</p>
    
    <div class="dashboard-links">
        <a href="exam_results.php">Enter Exam Results</a>
        <a href="recommendations.php"
           onclick="return checkResults(<?= $hasResults ? 'true' : 'false' ?>);">
           View Recommendations
        </a>
        <form action="reset_recommendations.php" method="POST" onsubmit="return confirm('Are you sure you want to reset your recommendations? This will delete your exam results.');" style="display:inline;">
            <button type="submit" class="reset-btn">Reset Recommendations</button>
        </form>
    </div>
</div>

<footer>
    &copy; 2025 Course Recommendation System | Inspired by KUCCPS
</footer>

<?php if (isset($_GET['success'])): ?>
<script>
    alert("<?= htmlspecialchars($_GET['success']) ?>");
</script>
<?php endif; ?>

</body>
</html>
