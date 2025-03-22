<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle deactivate/activate
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $user_id = $_GET['id'];

    if ($action == 'deactivate') {
        $sql = "UPDATE users SET is_active = 0 WHERE id = :id";
    } elseif ($action == 'activate') {
        $sql = "UPDATE users SET is_active = 1 WHERE id = :id";
    } elseif ($action == 'reset') {
        $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['password' => $defaultPassword, 'id' => $user_id]);
        header("Location: manage_users.php?reset=success");
        exit;
    }

    if ($action !== 'reset') {
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $user_id]);
        header("Location: manage_users.php");
        exit;
    }
}

// Fetch all students
$sql = "SELECT * FROM users WHERE role = 'student'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="manage_users_style.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_courses.php">Manage Courses</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Manage Users</h2>
    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
        <p style="color: green;">Password reset to default: <strong>password123</strong></p>
    <?php endif; ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['full_name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?></td>
            <td>
                <?php if ($user['is_active']): ?>
                    <a href="manage_users.php?action=deactivate&id=<?php echo $user['id']; ?>" class="deactivate-btn" onclick="return confirm('Deactivate this user?')">Deactivate</a>
                <?php else: ?>
                    <a href="manage_users.php?action=activate&id=<?php echo $user['id']; ?>" class="activate-btn" onclick="return confirm('Activate this user?')">Activate</a>
                <?php endif; ?>
                |
                <a href="manage_users.php?action=reset&id=<?php echo $user['id']; ?>" class="reset-btn" onclick="return confirm('Reset password to default (password123)?')">Reset</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>