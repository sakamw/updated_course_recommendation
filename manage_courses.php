<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Add or Edit Course
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = trim($_POST['course_name']);
    $university = trim($_POST['university']);
    $min_grade = $_POST['min_grade'];
    $required_subjects_input = $_POST['required_subjects'];
    
    // Convert comma-separated string to array
    $required_subjects = json_encode(array_map('trim', explode(',', $required_subjects_input)));

    // Check if course already exists with same name and university
    $checkSql = "SELECT * FROM courses WHERE course_name = :course_name AND university = :university";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute(['course_name' => $course_name, 'university' => $university]);
    $existingCourse = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['course_id']) && $_POST['course_id'] != "") {
        // Updating existing course
        $course_id = $_POST['course_id'];

        if ($existingCourse && $existingCourse['id'] != $course_id) {
            echo "<script>alert('Already exists!'); window.location='manage_courses.php';</script>";
            exit();
        }

        $sql = "UPDATE courses SET course_name = :course_name, university = :university, min_grade = :min_grade, required_subjects = :required_subjects WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'course_name' => $course_name,
            'university' => $university,
            'min_grade' => $min_grade,
            'required_subjects' => $required_subjects,
            'id' => $course_id
        ]);
    } else {
        // New course insert
        if ($existingCourse) {
            echo "<script>alert('Already exists!'); window.location='manage_courses.php';</script>";
            exit();
        }

        $sql = "INSERT INTO courses (course_name, university, min_grade, required_subjects) VALUES (:course_name, :university, :min_grade, :required_subjects)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'course_name' => $course_name,
            'university' => $university,
            'min_grade' => $min_grade,
            'required_subjects' => $required_subjects
        ]);
    }

    header("Location: manage_courses.php");
    exit();
}

// Delete Course
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $sql = "DELETE FROM courses WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $course_id]);
    header("Location: manage_courses.php");
    exit();
}

// Get All Courses
$sql = "SELECT * FROM courses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Courses</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="manage_courses_style.css">
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
    <h2>Manage Courses</h2>

    <!-- Add/Edit Course Form -->
    <form action="manage_courses.php" method="POST">
        <input type="hidden" name="course_id" id="course_id">
        <input type="text" name="course_name" id="course_name" placeholder="Course Name" required>
        <input type="text" name="university" id="university" placeholder="University" required>
        <input type="text" name="min_grade" id="min_grade" placeholder="Minimum Grade" required>
        <input type="text" name="required_subjects" id="required_subjects" placeholder="Required Subjects (comma separated)" required>
        <button type="submit">Save Course</button>
    </form>

    <!-- Display Existing Courses -->
    <table>
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>University</th>
            <th>Min Grade</th>
            <th>Subjects</th>
            <th>Action</th>
        </tr>
        <?php foreach ($courses as $course): ?>
        <tr>
            <td><?php echo $course['id']; ?></td>
            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
            <td><?php echo htmlspecialchars($course['university']); ?></td>
            <td><?php echo htmlspecialchars($course['min_grade']); ?></td>
            <td><?php echo htmlspecialchars(implode(", ", json_decode($course['required_subjects'], true))); ?></td>
            <td>
                <div class="action-buttons">
                    <a href="#" class="edit-btn" 
                        data-id="<?php echo $course['id']; ?>"
                        data-name="<?php echo htmlspecialchars($course['course_name']); ?>"
                        data-university="<?php echo htmlspecialchars($course['university']); ?>"
                        data-grade="<?php echo htmlspecialchars($course['min_grade']); ?>"
                        data-subjects="<?php echo htmlspecialchars(implode(", ", json_decode($course['required_subjects'], true))); ?>">Edit</a>

                    <a href="manage_courses.php?delete=<?php echo $course['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('course_id').value = this.dataset.id;
            document.getElementById('course_name').value = this.dataset.name;
            document.getElementById('university').value = this.dataset.university;
            document.getElementById('min_grade').value = this.dataset.grade;
            document.getElementById('required_subjects').value = this.dataset.subjects;
        });
    });
</script>

</body>
</html>
