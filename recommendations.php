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

if (empty($student_results)) {
    echo "<p style='color:red; text-align:center;'>No KCSE results found! Please enter your grades first.</p>";
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
    $grade = strtoupper($result['grade']); // Ensure uppercase
$student_scores[$result['subject']] = $grade_conversion[$grade] ?? 0; // Default to 0 if grade is missing

}

// Calculate Mean Grade (Avoid Division by Zero)
$average_score = (count($student_scores) > 0) ? array_sum($student_scores) / count($student_scores) : 0;

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
            $subject_match_score += $student_scores[$subject]; // Give points based on subject grades
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
            $best_reason = "This course was chosen because you scored well in " . implode(", ", $matched_subjects) . 
                ", and your mean grade meets the minimum requirement of " . $course['min_grade'] . ".";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Course Recommendations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="recommendations_style.css">
</head>
<body>
<div class="container">
    <h2>Recommended Courses & Universities</h2>

    <?php if (empty($recommended_courses)): ?>
        <p>No courses match your KCSE results. Try different subject combinations.</p>
    <?php else: ?>
        <h3 style="color: green;">🎯 Best Recommended Course</h3>
        <p><strong><?php echo htmlspecialchars($best_course['course_name']); ?></strong> at <strong><?php echo htmlspecialchars($best_course['university']); ?></strong></p>
        
        <p><strong>Reason for selection:</strong> <?php echo htmlspecialchars($best_reason); ?></p>

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

    <a href="dashboard.php">Go Back</a>
</div>
</body>
</html>
