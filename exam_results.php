<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subjects = $_POST['subjects'];
    $valid_grades = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'E'];

    $errors = [];

    // Validate compulsory
    $compulsory = ['Mathematics', 'English', 'Kiswahili'];
    foreach ($compulsory as $subject) {
        if (empty($subjects[$subject]) || !in_array(strtoupper($subjects[$subject]), $valid_grades)) {
            $errors[] = "$subject is required and must be a valid grade.";
        }
    }

    // Validate science - at least two
    $science = ['Biology', 'Chemistry', 'Physics'];
    $science_count = 0;
    foreach ($science as $subject) {
        if (!empty($subjects[$subject]) && in_array(strtoupper($subjects[$subject]), $valid_grades)) {
            $science_count++;
        }
    }
    if ($science_count < 2) {
        $errors[] = "You must enter at least two valid science subjects.";
    }

    // Validate all filled grades
    foreach ($subjects as $subject => $grade) {
        if (!empty($grade) && !in_array(strtoupper($grade), $valid_grades)) {
            $errors[] = "Invalid grade for $subject. Only grades A to E are accepted.";
        }
    }

    // If no errors, insert to DB
    if (empty($errors)) {
        foreach ($subjects as $subject => $grade) {
            if (!empty($grade)) {
                $sql = "INSERT INTO exam_results (user_id, subject, grade) VALUES (:user_id, :subject, :grade)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'user_id' => $user_id,
                    'subject' => $subject,
                    'grade' => strtoupper($grade)
                ]);
            }
        }

        header('Location: recommendations.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Enter KCSE Exam Results</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            const validGrades = ["A", "A-", "B+", "B", "B-", "C+", "C", "C-", "D+", "D", "D-", "E"];
            const form = document.forms[0];
            let errors = [];

            const comp = ['Mathematics', 'English', 'Kiswahili'];
            comp.forEach(sub => {
                const val = form[`subjects[${sub}]`].value.trim().toUpperCase();
                if (!validGrades.includes(val)) {
                    errors.push(`${sub} is required and must have a valid KCSE grade.`);
                }
            });

            const sciences = ['Biology', 'Chemistry', 'Physics'];
            let sciCount = 0;
            sciences.forEach(sub => {
                const val = form[`subjects[${sub}]`].value.trim().toUpperCase();
                if (validGrades.includes(val)) sciCount++;
                else if (val !== '') errors.push(`${sub} has an invalid grade.`);
            });

            if (sciCount < 2) {
                errors.push("Please enter at least two valid science subjects.");
            }

            // Check all others for invalid grade inputs
            const allSubs = ['History', 'Geography', 'Business Studies', 'Agriculture', 'Computer Studies', 'Other'];
            allSubs.forEach(sub => {
                const val = form[`subjects[${sub}]`].value.trim().toUpperCase();
                if (val !== '' && !validGrades.includes(val)) {
                    errors.push(`${sub} has an invalid grade.`);
                }
            });

            if (errors.length > 0) {
                alert(errors.join('\n'));
                return false;
            }
            return true;
        }
    </script>
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

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="exam_results.php" method="POST" onsubmit="return validateForm();">
        <h3>🟢 Compulsory Subjects</h3>
        <label>Mathematics:</label>
        <input type="text" name="subjects[Mathematics]" required>

        <label>English:</label>
        <input type="text" name="subjects[English]" required>

        <label>Kiswahili:</label>
        <input type="text" name="subjects[Kiswahili]" required>

        <h3>🧪 Science Subjects (Choose at least two)</h3>
        <label>Biology:</label>
        <input type="text" name="subjects[Biology]">

        <label>Chemistry:</label>
        <input type="text" name="subjects[Chemistry]">

        <label>Physics:</label>
        <input type="text" name="subjects[Physics]">

        <h3>📚 Humanities (Optional)</h3>
        <label>History:</label>
        <input type="text" name="subjects[History]">

        <label>Geography:</label>
        <input type="text" name="subjects[Geography]">

        <h3>🛠 Technical / Group IV Subjects</h3>
        <label>Business Studies:</label>
        <input type="text" name="subjects[Business Studies]">

        <label>Agriculture:</label>
        <input type="text" name="subjects[Agriculture]">

        <label>Computer Studies:</label>
        <input type="text" name="subjects[Computer Studies]">

        <label>Other (e.g., CRE, Home Science, Aviation):</label>
        <input type="text" name="subjects[Other]">

        <button type="submit">Submit Results</button>
    </form>
</div>

<footer>
    &copy; 2025 Course Recommendation System | Inspired by KUCCPS
</footer>

</body>
</html>