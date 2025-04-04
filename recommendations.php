<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student KCSE results
$sql = "SELECT subject, grade FROM exam_results WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$student_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle no results scenario
if (empty($student_results)) {
    echo "<p style='color:red; text-align:center;'>No KCSE results found! Please enter your grades first.</p>";
    echo "<p style='text-align:center;'><a href='dashboard.php'>Go Back</a></p>";
    exit();
}

// Grade Conversion (KCSE A-E to Numeric Values)
$grade_conversion = [
    'A' => 12, 'A-' => 11, 'B+' => 10, 'B' => 9, 'B-' => 8,
    'C+' => 7, 'C' => 6, 'C-' => 5, 'D+' => 4, 'D' => 3,
    'D-' => 2, 'E' => 1
];

// Convert student grades to numerical values
$student_scores = [];
foreach ($student_results as $result) {
    $grade = strtoupper(trim($result['grade'])); // Ensure uppercase
    if (isset($grade_conversion[$grade])) {
        $student_scores[$result['subject']] = $grade_conversion[$grade];
    }
}

// Calculate Mean Grade
$average_score = count($student_scores) > 0 ? array_sum($student_scores) / count($student_scores) : 0;

// Fetch courses from the database
$sql = "SELECT * FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$recommended_courses = [];
$best_course = null;
$best_score = 0;
$best_reason = "";

// Check eligibility for each course
foreach ($courses as $course) {
    $required_subjects = json_decode($course['required_subjects'], true);
    $min_grade = $grade_conversion[$course['min_grade']] ?? 0;

    $eligible = true;
    $subject_match_score = 0;
    $matched_subjects = [];

    foreach ($required_subjects as $subject) {
        if (!isset($student_scores[$subject]) || $student_scores[$subject] < 6) { // Min C Grade
            $eligible = false;
            break;
        } else {
            $subject_match_score += $student_scores[$subject];
            $matched_subjects[] = $subject;
        }
    }

    // Check Mean Grade Eligibility
    if ($average_score < $min_grade) {
        $eligible = false;
    }

    if ($eligible) {
        $recommended_courses[] = [
            'course_name' => $course['course_name'],
            'university' => $course['university'],
            'score' => $subject_match_score,
            'matched_subjects' => $matched_subjects,
            'min_grade' => $course['min_grade']
        ];

        // Pick the Best Course
        if ($subject_match_score > $best_score) {
            $best_score = $subject_match_score;
            $best_course = [
                'course_name' => $course['course_name'],
                'university' => $course['university']
            ];
            $best_reason = "This course is recommended based on your performance in " . implode(", ", $matched_subjects) . 
                " and meeting the minimum grade requirement of " . $course['min_grade'] . ".";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Recommendations</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .best-course {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #4CAF50;
        }
        .no-courses {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #f44336;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Course Recommendations</h2>

    <?php if (empty($recommended_courses)): ?>
        <div class="no-courses">
            <h3>No Matching Courses Found</h3>
            <p>Based on your KCSE results, we couldn't find courses that match your qualifications.</p>
            <p>Consider the following options:</p>
            <ul>
                <li>Explore diploma or certificate programs with different entry requirements</li>
                <li>Consider technical and vocational training programs</li>
                <li>Consult with an academic advisor for personalized guidance</li>
            </ul>
        </div>
    <?php else: ?>
        <div class="best-course">
            <h3>Best Recommended Course</h3>
            <p><strong><?php echo htmlspecialchars($best_course['course_name']); ?></strong> at <strong><?php echo htmlspecialchars($best_course['university']); ?></strong></p>
            <p><?php echo htmlspecialchars($best_reason); ?></p>
        </div>

        <h3>Other Eligible Courses</h3>
        <table>
            <tr>
                <th>Course Name</th>
                <th>University</th>
            </tr>
            <?php foreach ($recommended_courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($course['university']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>
</body>
</html>