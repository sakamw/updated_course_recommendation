<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

// Update profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET full_name = :full_name, email = :email WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'full_name' => $full_name,
        'email' => $email,
        'id' => $user_id
    ]);

    // Handle password update
    if (!empty($_POST['new_password'])) {
        $new_password = $_POST['new_password'];

        // Password strength validation
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $new_password)) {
            $error = "Password must be at least 8 characters and include letters, numbers, and symbols.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :password, must_change_password = 0 WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['password' => $hashed_password, 'id' => $user_id]);
            $success = "Password successfully updated!";
        }
    }

    if (!isset($error)) {
        header('Location: dashboard.php');
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2>My Profile</h2>

    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>

    <form action="profile.php" method="POST">
        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <hr style="margin: 20px 0; width: 100%; border: 1px dashed #ccc;">

        <label>New Password:</label>
            <div style="position: relative; width: 90%;">
                <input type="password" name="new_password" id="new_password" placeholder="New Strong Password" style="width: 100%;">
                <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 10px; cursor: pointer; color: #555;">👁️</span>
            </div>

            <small style="color: gray; display: block; margin-bottom: 15px;">
                Password must be at least 8 characters long and include a number, a letter, and a symbol.
            </small>
        <button type="submit">Update Profile</button>
    </form>
</div>

<footer>
    &copy; 2025 Course Recommendation System | Inspired by KUCCPS
</footer>

<?php if (isset($_GET['success']) && $_GET['success']): ?>
<script>
    alert("<?php echo htmlspecialchars($_GET['success']); ?>");
</script>
<?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error']): ?>
<script>
    alert("<?php echo htmlspecialchars($_GET['error']); ?>");
</script>
<?php endif; ?>

<script>
function togglePassword() {
    const passwordField = document.getElementById('new_password');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
    } else {
        passwordField.type = 'password';
    }
}
</script>
</body>
</html>