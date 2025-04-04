<?php
session_start();
include 'db.php';

// Define valid grades
$kcse_grades = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'E'];

// Function to generate a dropdown for grades
function gradeDropdown($subject, $selected = '', $onchange = '') {
    global $kcse_grades;
    $html = "<select name=\"subjects[$subject]\" $onchange>";
    $html .= "<option value=\"\">-- Select Grade --</option>";
    foreach ($kcse_grades as $grade) {
        $isSelected = ($selected === $grade) ? 'selected' : '';
        $html .= "<option value=\"$grade\" $isSelected>$grade</option>";
    }
    $html .= "</select>";
    return $html;
}

// Form validation and processing
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subjects = $_POST['subjects'];

    // Compulsory
    $compulsory = ['Mathematics', 'English', 'Kiswahili'];
    foreach ($compulsory as $subject) {
        if (empty($subjects[$subject]) || !in_array($subjects[$subject], $kcse_grades)) {
            $errors[] = "$subject is required and must be a valid grade.";
        }
    }

    // Sciences (min 2)
    $science = ['Biology', 'Chemistry', 'Physics'];
    $science_count = 0;
    foreach ($science as $subject) {
        if (!empty($subjects[$subject]) && in_array($subjects[$subject], $kcse_grades)) {
            $science_count++;
        }
    }
    if ($science_count < 2) {
        $errors[] = "You must enter at least two valid science subjects.";
    }

    // Technical (exactly 1)
    $technical = ['Business Studies', 'Agriculture', 'Computer Studies', 'Other'];
    $technical_count = 0;
    foreach ($technical as $subject) {
        if (!empty($subjects[$subject]) && in_array($subjects[$subject], $kcse_grades)) {
            $technical_count++;
        }
    }
    if ($technical_count != 1) {
        $errors[] = "You must select exactly one technical subject.";
    }

    // Count valid grades and check range
    $filled_subjects = 0;
    foreach ($subjects as $subject => $grade) {
        if (!empty($grade)) {
            if (!in_array($grade, $kcse_grades)) {
                $errors[] = "Invalid grade for $subject.";
            }
            $filled_subjects++;
        }
    }

    if ($filled_subjects < 7 || $filled_subjects > 8) {
        $errors[] = "You must enter between 7 and 8 subjects.";
    }

    // Save if no errors
    if (empty($errors)) {
        foreach ($subjects as $subject => $grade) {
            if (!empty($grade)) {
                $sql = "INSERT INTO exam_results (user_id, subject, grade) VALUES (:user_id, :subject, :grade)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'user_id' => $user_id,
                    'subject' => $subject,
                    'grade' => $grade
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
            const form = document.forms[0];
            const validGrades = <?= json_encode($kcse_grades) ?>;
            let errors = [];

            const comp = ['Mathematics', 'English', 'Kiswahili'];
            comp.forEach(sub => {
                const val = form[`subjects[${sub}]`].value;
                if (!validGrades.includes(val)) {
                    errors.push(`${sub} is required and must be a valid grade.`);
                }
            });

            const sciences = ['Biology', 'Chemistry', 'Physics'];
            let sciCount = 0;
            sciences.forEach(sub => {
                const val = form[`subjects[${sub}]`].value;
                if (validGrades.includes(val)) sciCount++;
                else if (val !== '') errors.push(`${sub} has an invalid grade.`);
            });
            if (sciCount < 2) {
                errors.push("Please enter at least two valid science subjects.");
            }

            const technicals = ['Business Studies', 'Agriculture', 'Computer Studies', 'Other'];
            let techCount = 0;
            technicals.forEach(sub => {
                const val = form[`subjects[${sub}]`].value;
                if (validGrades.includes(val)) techCount++;
                else if (val !== '') errors.push(`${sub} has an invalid grade.`);
            });
            if (techCount !== 1) {
                errors.push("Please select exactly one technical subject.");
            }

            const humanities = ['History', 'Geography'];
            humanities.forEach(sub => {
                const val = form[`subjects[${sub}]`].value;
                if (val !== '' && !validGrades.includes(val)) {
                    errors.push(`${sub} has an invalid grade.`);
                }
            });

            // Count all filled valid grades
            let filled = 0;
            [...comp, ...sciences, ...technicals, ...humanities].forEach(sub => {
                const val = form[`subjects[${sub}]`].value;
                if (validGrades.includes(val)) filled++;
            });

            if (filled < 7 || filled > 8) {
                errors.push("You must enter between 7 and 8 subjects.");
            }

            if (errors.length > 0) {
                alert(errors.join('\n'));
                return false;
            }
            return true;
        }

        function checkTechnicalSubject(currentSelect) {
            const technicals = ['Business Studies', 'Agriculture', 'Computer Studies', 'Other'];
            const form = document.forms[0];
            if (currentSelect.value !== "") {
                technicals.forEach(sub => {
                    const select = form[`subjects[${sub}]`];
                    if (select !== currentSelect) {
                        select.value = "";
                    }
                });
            }
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
        <script>
            alert("<?= implode('\n', array_map('htmlspecialchars', $errors)) ?>");
        </script>
    <?php endif; ?>

    <form action="exam_results.php" method="POST" onsubmit="return validateForm();">
        <h3>🟢 Compulsory Subjects</h3>
        <label>Mathematics:</label>
        <?= gradeDropdown('Mathematics', $_POST['subjects']['Mathematics'] ?? '') ?>

        <label>English:</label>
        <?= gradeDropdown('English', $_POST['subjects']['English'] ?? '') ?>

        <label>Kiswahili:</label>
        <?= gradeDropdown('Kiswahili', $_POST['subjects']['Kiswahili'] ?? '') ?>

        <h3>🧪 Science Subjects (Choose at least two)</h3>
        <label>Biology:</label>
        <?= gradeDropdown('Biology', $_POST['subjects']['Biology'] ?? '') ?>

        <label>Chemistry:</label>
        <?= gradeDropdown('Chemistry', $_POST['subjects']['Chemistry'] ?? '') ?>

        <label>Physics:</label>
        <?= gradeDropdown('Physics', $_POST['subjects']['Physics'] ?? '') ?>

        <h3>📚 Humanities (Optional)</h3>
        <label>History:</label>
        <?= gradeDropdown('History', $_POST['subjects']['History'] ?? '') ?>

        <label>Geography:</label>
        <?= gradeDropdown('Geography', $_POST['subjects']['Geography'] ?? '') ?>

        <h3>🛠 Technical / Group IV Subjects (Select exactly one)</h3>
        <label>Business Studies:</label>
        <?= gradeDropdown('Business Studies', $_POST['subjects']['Business Studies'] ?? '', 'onchange="checkTechnicalSubject(this)"') ?>

        <label>Agriculture:</label>
        <?= gradeDropdown('Agriculture', $_POST['subjects']['Agriculture'] ?? '', 'onchange="checkTechnicalSubject(this)"') ?>

        <label>Computer Studies:</label>
        <?= gradeDropdown('Computer Studies', $_POST['subjects']['Computer Studies'] ?? '', 'onchange="checkTechnicalSubject(this)"') ?>

        <label>Other (e.g., CRE, Home Science, Aviation):</label>
        <?= gradeDropdown('Other', $_POST['subjects']['Other'] ?? '', 'onchange="checkTechnicalSubject(this)"') ?>

        <button type="submit">Submit Results</button>
    </form>
