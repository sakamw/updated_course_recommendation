<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    foreach ($_POST['subjects'] as $subject => $grade) {
        if (!empty($grade)) {
            $sql = "INSERT INTO exam_results (user_id, subject, grade) VALUES (:user_id, :subject, :grade)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['user_id' => $user_id, 'subject' => $subject, 'grade' => strtoupper($grade)]);
        }
    }

    header('Location: recommendations.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Enter KCSE Exam Results</title>
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
    <h2>Enter KCSE Exam Results</h2>
    <form action="exam_results.php" method="POST">
        <h3>🟢 Compulsory Subjects</h3>
        <label>Mathematics:</label>
        <input type="text" name="subjects[Mathematics]" required>

        <label>English:</label>
        <input type="text" name="subjects[English]" required>

        <label>Kiswahili:</label>
        <input type="text" name="subjects[Kiswahili]" required>

        <h3>🟢 Science Subjects (Choose at least two)</h3>
        <label>Biology:</label>
        <input type="text" name="subjects[Biology]">

        <label>Chemistry:</label>
        <input type="text" name="subjects[Chemistry]">

        <label>Physics:</label>
        <input type="text" name="subjects[Physics]">

        <h3>🟢 Humanities (Choose at least one)</h3>
        <label>History:</label>
        <input type="text" name="subjects[History]">

        <label>Geography:</label>
        <input type="text" name="subjects[Geography]">

        <!-- <label>CRE / IRE / HRE:</label>
        <select name="subjects[Religious Studies]">
            <option value="">--Select--</option>
            <option value="CRE">Christian Religious Education (CRE)</option>
            <option value="IRE">Islamic Religious Education (IRE)</option>
            <option value="HRE">Hindu Religious Education (HRE)</option>
        </select> -->

        <h3>🟢 Technical / Group IV Subjects (Choose at least one)</h3>
        <label>Business Studies:</label>
        <input type="text" name="subjects[Business Studies]">

        <label>Agriculture:</label>
        <input type="text" name="subjects[Agriculture]">

        <label>Computer Studies:</label>
        <input type="text" name="subjects[Computer Studies]">

        <label>Other (e.g., Home Science, Aviation):</label>
        <input type="text" name="subjects[Other]">

        <button type="submit">Submit Results</button>
    </form>
</div>

<footer>
    &copy; 2025 Course Recommendation System | Inspired by KUCCPS
</footer>

</body>
</html>
